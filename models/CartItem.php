<?php
/**
 * models/CartItem.php
 */
require_once __DIR__ . '/../config/database.php';

class CartItemModel {
    public int $id;
    public int $userId;
    public int $foodId;
    public int $quantity;
    public float $price;
    public string $foodName;
}
