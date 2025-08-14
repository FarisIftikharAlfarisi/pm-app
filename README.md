# 🏗️ SIMAPRO (Construction Project Management System)

**Author:** Faris Iftikhar Alfarisi  

SIMAPRO is an integrated system designed to manage the entire lifecycle of construction projects, from planning to completion. It is specifically tailored to meet the needs of construction companies in monitoring project progress, managing resources, and optimizing procurement processes.

---

## 🚀 Key Features
- **Inventory Management** – Real-time tracking and control of raw materials, equipment, and workshop products.
- **Supplier Selection & Purchasing** – Selecting the best suppliers using the Weighted Product method, with integrated Purchase Requisition (PR) and Purchase Order (PO) creation.
- **Project Progression** – Monitoring work schedules based on the Work Breakdown Structure (WBS), including predecessor settings, duration, and resource requirements.
- **Labour Management** – Managing workforce allocation based on project schedules and requirements.
- **Project Financial Management** – Recording and tracking project expenses to ensure budget efficiency.
- **Workshop Production Scheduling** – Scheduling the production of custom items (Aftercraft) according to project plans and material availability.

With SIMAPRO, the entire construction project process becomes more structured, transparent, and efficient, reducing the risks of delays and unnecessary costs.

---

## 📥 How to Clone & Run Locally

### 1️⃣ Clone Repository
```bash
git clone https://github.com/FarisIftikharAlfarisi/pm-app.git
```

2️⃣ Navigate to Project Folder
```bash
cd pm-app
```
3️⃣ Install Dependencies
bash
Salin
Edit
composer install
npm install
4️⃣ Copy .env & Configure
bash
Salin
Edit
cp .env.example .env
Edit .env file to set your database, mail, and other configurations.

5️⃣ Generate Application Key
bash
Salin
Edit
php artisan key:generate
6️⃣ Run Database Migration & Seeder
bash
Salin
Edit
php artisan migrate --seed
7️⃣ Start the Application
bash
Salin
Edit
php artisan serve
```
