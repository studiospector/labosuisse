<?php

namespace Caffeina\LaboSuisse\Resources\Woocommerce;

use Caffeina\LaboSuisse\Option\Option;
use WC_Order;
use WC_Order_Query;

class OrderExport
{
    /**
     * @var bool
     */
    private bool $enable;

    public function __construct()
    {
        $this->enable = (new Option())->orderExportIsActive();
    }

    public function start()
    {
        if (!$this->enable) {
            return null;
        }

        $orders = $this->getOrders();

        if (empty($orders)) {
            $this->sendMail('Nessun ordine da esportare', null);
        } else {
            $filePath = $this->makeFile($orders);
            $this->sendMail('Export Ordini', $filePath);
            unlink($filePath);
        }
    }

    /**
     * @return array|object
     * @throws \Exception
     */
    private function executeQuery(): array|object
    {
        $query = new WC_Order_Query([
            'limit' => -1,
            'return' => 'ids',
            'status' => 'processing',
//            'date_query' => array(
//                'after' => date('Y-m-d', strtotime('-1 days')),
//                'before' => date('Y-m-d', strtotime('today'))
//            )
        ]);

        return $query->get_orders();
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getOrders()
    {
        $orders = $this->executeQuery();

        if (empty($orders)) {
            return [];
        }

        $items = [];
        foreach ($orders as $order_id) {
            $order = wc_get_order($order_id);
            $orderDetails = $this->getOrderDetails($order_id, $order);
            $products = $this->getProductsDetails($order);

            $data = array_map(function ($items) use ($orderDetails) {
                return array_merge($orderDetails, $items);
            }, $products);

            $items = array_merge($items, $data);
        }

        return $items;
    }

    /**
     * @param mixed $order_id
     * @param WC_Order $order
     * @return array
     */
    private function getOrderDetails(mixed $order_id, WC_Order $order): array
    {
        return [
            'order_id' => $order_id,
            'created_at' => $order->get_date_created(),
            'shipping_name' => ucfirst($order->get_shipping_first_name()) . " " . ucfirst($order->get_shipping_last_name()),
            'shipping_address' => $order->get_shipping_address_1() . " " . $order->get_shipping_address_2(),
            'shipping_city' => $order->get_shipping_city(),
            'shipping_state' => $order->get_shipping_state(),
            'shipping_country' => $order->get_shipping_country(),
            'shipping_postcode' => $order->get_shipping_postcode()
        ];
    }

    /**
     * @param WC_Order $order
     * @return array
     */
    private function getProductsDetails(WC_Order $order): array
    {
        $items = [];

        foreach ($order->get_items() as $i => $item) {
            $productId = $item->get_variation_id()
                ? $item->get_variation_id()
                : $item->get_product_id();

            $items[] = [
                'product_id' => $productId,
                'sku' => wc_get_product($productId)->get_sku(),
                'product_name' => strip_tags($item->get_name()),
                'quantity' => $item->get_quantity(),
                'subtotal' => $item->get_subtotal(),
                'total' => $item->get_total(),
            ];
        }

        return $items;
    }

    /**
     * @param $orders
     * @return string|null
     */
    private function makeFile($orders): ?string
    {
        if (count($orders) == 0) {
            return null;
        }

        $filePath = "/tmp/" . uniqid() . '.csv';
        $fp = fopen($filePath, 'w');

        //columns name
        fputcsv($fp, array_keys($orders[0]));

        foreach ($orders as $order) {
            fputcsv($fp, array_values($order));
        }

        return $filePath;
    }

    /**
     * @param string $message
     * @param string|null $filePath
     * @return void
     */
    private function sendMail(string $message, ?string $filePath): void
    {
        $to = (new Option())->getOrderExportMailingList();

        $subject = 'Export ordini - ' . date('Y-m-d H:i');

        wp_mail($to, $subject, $message, null, $filePath);
    }
}
