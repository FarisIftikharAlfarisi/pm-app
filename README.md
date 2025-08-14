#SIMAPRO (Construction Project Management System)

**Author:** Faris Iftikhar Alfarisi  

SIMAPRO is an integrated system designed to manage the entire lifecycle of construction projects, from planning to completion. It is specifically tailored to meet the needs of construction companies in monitoring project progress, managing resources, and optimizing procurement processes.

---

## Key Features
- **Inventory Management**
  Real-time tracking and control of raw materials, equipment, and workshop products.
- **Supplier Selection & Purchasing**
  Selecting the best suppliers using the Weighted Product method, with integrated Purchase Requisition (PR) and Purchase Order (PO) creation.
- **Project Progression**
  Monitoring work schedules based on the Work Breakdown Structure (WBS), including predecessor settings, duration, and resource requirements.
- **Labour Management**
  Managing workforce allocation based on project schedules and requirements.
- **Project Financial Management**
  Recording and tracking project expenses to ensure budget efficiency.
- **Workshop Production Scheduling**
  Scheduling the production of custom items (Aftercraft) according to project plans and material availability.

With SIMAPRO, the entire construction project process becomes more structured, transparent, and efficient, reducing the risks of delays and unnecessary costs.

---

## 📥 How to Clone & Run Locally

### Clone Repository
```bash
git clone https://github.com/FarisIftikharAlfarisi/pm-app.git
```

Navigate to Project Folder
```bash
cd pm-app
```
Install Dependencies
```bash
composer install
npm install
```
Copy .env & Configure
```bash
cp .env.example .env
```
Edit .env file to set your database, mail, and other configurations.

Generate Application Key
```bash
php artisan key:generate
```

Run Database Migration & Seeder
```bash
php artisan migrate --seed
```
Start the Application
```bash
php artisan serve
```
