# Profit Calculation for WooCommerce

A lightweight WooCommerce extension that helps store owners calculate profit margins by adding a Buying Price field to products and tracking it across orders.

## Description

The **Profit Calculation** plugin allows shop administrators to set a "Buying Price" (cost price) for their products. When an order is placed, this cost is recorded in the order item metadata to ensure historical accuracy, even if the product's cost changes later. The plugin provides a dedicated admin dashboard to view the profit per order and the total profit for visible orders.

## Features

### 💰 Profit Management
- **Buying Price Field**: Adds a "Buying Price (Before Profit)" field to the General tab of WooCommerce Product Data.
- **Order Snapshot**: Automatically saves the Buying Price to the order item metadata (`_samrprca_buying_price`) when an order is placed. This protects your reports from future cost price changes.

### 📊 Profit Reporting
- **Dedicated Dashboard**: Accessible via `WooCommerce > Profit Calculation`.
- **Detailed Table**: Lists orders with Selling Price, Buying Price, and calculated Profit.
- **Total Profit**: Automatically calculates and displays the total profit for all valid orders.
- **Smart Filtering**: Only shows orders that contain valid Buying Price data to ensure calculation accuracy.

## Installation

1. Upload the plugin files to `/wp-content/plugins/profit-calculation/`.
2. Run `composer install` (or `composer dump-autoload` if dependencies are already present) to generate the autoloader.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Go to any Product > General Tab and set the "Buying Price".

## Requirements

- WordPress 5.0 or higher
- WooCommerce 5.0.0 or higher
- PHP 7.4 or higher

## Usage

1. **Set Buying Price**: Edit a product and enter the cost in the "Buying Price (Before Profit)" field.
2. **Place Orders**: As customers purchase products, the cost is recorded.
3. **View Profits**: Navigate to `WooCommerce > Profit Calculation` to see your earnings.

## File Structure

```
profit-calculation/
├── assets/
│   ├── css/            # Admin styles
│   └── js/             # Admin scripts (validation)
├── includes/
│   ├── Assets.php      # Asset management
│   ├── ProductMeta.php # Product field & Order meta logic
│   ├── ProfitCalculation.php # Main class & container
│   ├── ProfitListTable.php   # WP_List_Table implementation
│   └── ProfitTable.php       # Admin page renderer
├── templates/
│   └── profit-index.php      # Dashboard template
├── vendor/             # Composer dependencies
├── composer.json       # Composer config
└── profit-calculation.php # Main plugin file
```

## Changelog

### Version 0.0.1
- Initial release.
- Added Buying Price field to products.
- Implemented Order Item meta snapshot.
- Added Profit Calculation admin table.
- Added Total Profit display.

## License

This plugin is licensed under GPL2.
