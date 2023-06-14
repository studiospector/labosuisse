<?php

namespace GtmEcommerceWooPro\Lib\Type;

class EventType {
	const ABANDON_CART = 'abandon_cart';

	const ABANDON_CHECKOUT = 'abandon_checkout';

	const ADD_BILLING_INFO = 'add_billing_info';

	const ADD_PAYMENT_INFO = 'add_payment_info';

	const ADD_SHIPPING_INFO = 'add_shipping_info';

	const ADD_TO_CART = 'add_to_cart';

	const BEGIN_CHECKOUT = 'begin_checkout';

	const PURCHASE = 'purchase';

	const REFUND = 'refund';

	const REMOVE_FROM_CART = 'remove_from_cart';

	const SELECT_ITEM = 'select_item';

	const VIEW_CART = 'view_cart';

	const VIEW_ITEM = 'view_item';

	const VIEW_ITEM_LIST = 'view_item_list';
}
