<?php

namespace Src\Models;

readonly class OrderItem
{
	public function __construct(
		public ?int  $id,
		public int   $orderId,
		public int   $productId,
		public int   $quantity,
		public float $soldPrice
	){}
}


