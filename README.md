# System Limits Module for Perfex CRM

A lightweight, non-SaaS module to apply **global system limits** in Perfex CRM.
This module allows the **Administrator** to define how many records can be created
across core Perfex resources. When a limit is reached, creation is blocked and a
proper alert message is shown.

---

## ✅ Compatible With

- **Perfex CRM:** 3.1.6
- **PHP:** 8.1.x
- **Database:** MySQL / MariaDB
- **Environment:** Single-instance (non-SaaS)

---

## 🚀 Features

Admin can set global limits for:

- Leads
- Staff
- Customers
- Proposals
- Estimates
- Invoices
- Projects
- Tasks
- Media (files / attachments)

### Behaviour
- Limits are enforced via **Perfex hooks** (no core file modification)
- When limit is reached:
  - New record will **NOT** be created
  - User will see a **clear alert message**
- Works with:
  - Normal form submit
  - AJAX requests
- Super Admin is **unlimited by default**

---

## 📁 Module Structure

```
modules/system_limits/
├── system_limits.php
├── config/
│   └── autoload.php
├── controllers/
│   └── System_limits.php
├── helpers/
│   └── system_limits_helper.php
├── hooks/
│   └── system_limits_hooks.php
├── models/
│   └── system_limits_model.php
├── views/
│   └── settings.php
├── language/
│   └── english/
│       └── system_limits_lang.php
├── migrations/
│   └── 110_version_1_1_0.php
└── README.md
```

---

## ⚙️ Installation

1. Upload the module folder:
   ```
   modules/system_limits/
   ```

2. Go to:
   ```
   Admin → Setup → Modules
   ```

3. Activate **System Limits**

4. Open settings:
   ```
   Admin → Setup → System Limits
   ```
   or
   ```
   /admin/system_limits
   ```

---

## 🗄️ Database Table

The module automatically creates this table:

```
tblsystem_limits
```

### Columns

| Column       | Type     | Description |
|-------------|----------|-------------|
| id          | int      | Primary key |
| resource    | varchar  | Resource name (leads, tasks, etc.) |
| limit_value | int      | Max allowed (NULL or 0 = unlimited) |
| is_enabled  | tinyint  | 1 = enforce limit |
| updated_at  | datetime | Auto-managed |

> ⚠️ If a wrong table name like `tbltblsystem_limits` exists, rename it:
```sql
RENAME TABLE tbltblsystem_limits TO tblsystem_limits;
```

---

## 🔒 Limit Enforcement Logic

Limits are enforced using **Perfex hooks**, for example:

- `before_lead_added`
- `before_add_project`
- `before_add_task`
- `before_invoice_added`
- Upload hooks for media
- Additional hooks for **Copy / Clone Project**

This ensures:
- No bypass via UI
- No bypass via AJAX
- No core file modification

---

## 🧠 Special Cases Handled

### ✔ Save button error (fixed)
- Handles missing `updated_at` column gracefully
- Auto-adds column if missing

### ✔ AJAX JSON error (fixed)
- Prevents `"[object Object]" is not valid JSON`
- Uses correct HTTP status for Perfex JS handler

### ✔ Copy Project limit
- Limit enforced during:
  - Copy Project
  - Clone Project (where hook is available)

### ✔ Task error message fix
- Prevents misleading “Task not found”
- Redirects correctly to task list

---

## 🌍 Language Support

Default language file:
```
modules/system_limits/language/english/system_limits_lang.php
```

You can add other languages by copying this file.

---

## ❌ What This Module Is NOT

- ❌ Not a SaaS / tenant module
- ❌ No subscription handling
- ❌ No billing
- ❌ No per-staff custom limits (global only)

---

## 🛠️ Customization Tips

- To make **Admin also limited**, edit:
  ```
  helpers/system_limits_helper.php
  ```
- To add new resource limits, add:
  - DB count logic
  - Hook mapping

---

## 📌 Version History

### v1.1.4 (Current)
- Fixed double `tbltbl` prefix issue
- Stable table creation
- Fully working save + alerts
- Copy Project support
- PHP 8.1 compatible

---

## 👨‍💻 Author Notes

This module is intentionally kept **simple, clean, and stable**.
Designed for real-world Perfex CRM usage without SaaS complexity.

---

## ✅ Status

✔ Production-ready  
✔ Tested on Perfex CRM 3.1.6  
✔ PHP 8.1 compatible  
