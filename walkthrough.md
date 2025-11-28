# Database Integration Walkthrough

I have successfully connected the landing page (`app/View/index.php`) to the database. The content for Features, Stats, Testimonials, and FAQs is now dynamic.

## Changes Made

### 1. Created Models
I created the following models in `app/Model/` to fetch data from the database:
- `Feature.php`: Fetches features list.
- `Stat.php`: Fetches statistics.
- `Testimonial.php`: Fetches user testimonials.
- `Faq.php`: Fetches frequently asked questions.

### 2. Database Setup
I created a setup script `setup_landing_tables.php` (and executed it) to:
- Create tables: `features`, `stats`, `testimonials`, `faqs`.
- Insert the initial data that was previously hardcoded in the view.

### 3. Updated Controller
I updated `app/Controller/HomeController.php` to:
- Import the new models.
- Fetch data using `getAll()` methods.
- Pass the data to the `index` view.

### 4. Updated View
I updated `app/View/index.php` to:
- Remove hardcoded HTML content.
- Use PHP loops to iterate over the data passed from the controller.
- Display the data dynamically.

## Verification
- The database tables have been created and populated.
- The controller now fetches data from the DB.
- The view now renders data from the DB.

You can now manage the landing page content by updating the database tables directly.
