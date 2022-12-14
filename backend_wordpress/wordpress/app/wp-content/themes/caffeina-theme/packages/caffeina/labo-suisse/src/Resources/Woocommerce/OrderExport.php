<?php

namespace Caffeina\LaboSuisse\Resources\Woocommerce;

use WC_Order;
use WC_Order_Query;

class OrderExport
{
    public function start()
    {
        $orders = [];
        foreach ($this->orders() as $order_id) {
            $order = wc_get_order($order_id);
            $orderDetails = $this->getOrderDetails($order_id, $order);
            $products = $this->getProductsDetails($order);

            $items = array_map(function ($items) use ($orderDetails) {
                return array_merge($orderDetails, $items);
            }, $products);

            $orders = array_merge($orders, $items);
        }

        $filePath = $this->makeFile($orders);

        $this->sendMail($filePath);

        unlink($filePath);
    }

    /**
     * @return array|object
     * @throws \Exception
     */
    private function orders(): array|object
    {
        $query = new WC_Order_Query([
            'limit' => -1,
            'return' => 'ids',
//            'date_completed' => '2018-10-01...2018-10-10',
            'status' => 'processing'
        ]);

        return $query->get_orders();

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
     * @param string|null $filePath
     * @return void
     */
    private function sendMail(?string $filePath): void
    {
        $to = [
            'matteo.meloni@caffeina.com'
        ];

        $subject = 'Export ordini - ' . date('Y-m-d H:i');

        wp_mail($to, $subject, 'Export Ordini', null, $filePath);
    }
}
