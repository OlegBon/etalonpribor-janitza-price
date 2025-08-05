# 💰 Dynamic Pricing Integration for WordPress Product Pages

This repository adds dynamic pricing and availability display to WordPress product pages using JavaScript and external JSON data. The integration is designed for flexibility and works with child themes as well as main themes.

## 📁 Project Structure

```text
code-for-price/
├── wp-content/
│   ├── themes/
│   │   └── child-theme/
│   │       ├── functions.php
│   │       ├── style.css
│   │       └── js/
│   │           └── device-init.js
│   └── uploads/
│       └── _data-pribor.js
├── export/
└── README.md
```

## 🧩 Purpose

Display device pricing, availability, and last updated date on product pages based on URL matching. Data is defined externally to avoid manual edits in WordPress admin and allow easy updates.

## ⚙️ How It Works

- `_data-pribor.js` contains an array of device objects with fields:

  - `name`
  - `locale`
  - `url`
  - `price`
  - `availability`
  - `kod1c: artikul`
  - `updated`

- device-init.js finds the matching device based on window.location.pathname and fills the #device-info HTML block.
- functions.php loads both scripts on the frontend and ensures dependency order.

## 🖼 Example HTML Block

```html
<div id="device-info">
  <p>
    Price: <span id="price"></span> ₴ on <span id="updated"></span><br />
    Artikul: <span id="kod1c"></span><br />
    <span id="availability"></span>
  </p>
</div>
```

## 🧠 Notes on Theme Integration

This setup assumes usage inside a child theme, typically located at:

```text
/wp-content/themes/child-theme/
```

If you’re not using a child theme:

- Move device-init.js, functions.php, and style.css into your main theme directory.
- Update get_stylesheet_directory_uri() to get_template_directory_uri() if needed.

> **✅ Using a child theme is recommended to keep core theme files untouched during updates.**

## 🪄 Tips

- Format prices using Intl.NumberFormat('uk-UA') for visual clarity (`15 103 ₴`).

## 📦 Future Ideas

- Localization via locale integration with Polylang
- Fallback UI if product not found
- Preloader animation while data

## 🧾 Integration with 1C

Product data is sourced from the 1C system and loaded into WordPress via a JavaScript array called `deviceData`, defined in the `_data-pribor.js` file. This allows for dynamic updates of pricing, availability, and product codes without modifying WordPress admin content.

### 📥 1C Data Format - dir export

```js
deviceData = [
  {
    locale: "uk",
    name: "UMG 96-S2",
    url: "/umg-96-s2/",
    price: 16314,
    availability: "Є в наявності",
    updated: "2025-08-05",
    kod1c: 15617,
  },
  ...
];
```

Each object includes:

- `locale`: Page language
- `name`: Device name
- `url`: Product page path
- `price`: Price from 1C
- `availability`: Stock status
- `updated`: Last updated date
- `kod1c`: Unique article code from 1C

### 🔗 Automatic Updates

- `_data-pribor.js` can be generated or synced via API/export from 1C.
- `device-init.js` runs on the frontend, matches the product based on `window.location.pathname`, and updates the `#device-info` block dynamically.

> This decouples pricing logic from WordPress, ensuring content stays current while keeping the admin interface clean.

## Autor

- [Oleg Bon](https://github.com/OlegBon)
