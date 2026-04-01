
# IranWatch

**IranWatch** is a web platform delivering news and historical analysis of the Iran‑USA‑Israel conflict.  
Built with pure PHP (no framework) following MVC architecture, it focuses on clean code, performance (file‑based caching), and SEO best practices.

![PHP Version](https://img.shields.io/badge/PHP-8.2-blue) ![MySQL](https://img.shields.io/badge/MySQL-8.0-orange) ![License](https://img.shields.io/badge/License-MIT-green)

---

## screenshots

| **Front Office** | **Back Office** |
|------------------|-----------------|
| ![Welcome](screenshoot/front_welcome.jpg) | ![Content list](screenshoot/Backoffice_liste.jpg) |
| ![Article list](screenshoot/front_scroll.jpg) | ![Create form](screenshoot/create_content_Un.jpg) |
| ![Article detail](screenshoot/front_show.jpg) | ![Edit form](screenshoot/modif_content_Un.jpg) |
| ![History page](screenshoot/Histoire.jpg) | *(more screenshoot available in the `screenshoot/` folder)* |


## Key Features

- **Pure PHP MVC** – no framework, easy to understand and extend.
- **Dynamic content** – manage sections, articles, and images through a MySQL database.
- **File‑based caching** – reduces database load, improves response time.
- **SEO‑ready** – custom meta tags, Open Graph, JSON‑LD, canonical URLs, and a generated sitemap.
- **Responsive design** – works on all devices.
- **Secure admin area** – login, image uploads, and rich text editing (TinyMCE).

---

## Tech Stack

- PHP 8.2 + PDO
- MySQL 8.0
- HTML5 / CSS3 (Grid, Flexbox)
- TinyMCE
- Composer (autoload)
- Docker (optional)

---

##  Quick Start

1. **Clone**  
   ```bash
   git clone https://github.com/your-username/iranwatch.git
   cd iranwatch
   ```

2. **Configure**  
   - Create a MySQL database (e.g. `ww3`).  
   - Copy `.env.example` to `.env` and fill in your database credentials.  
   - Import the database schema (provided in `database/` folder).

3. **Install dependencies**  
   ```bash
   composer install
   ```

4. **Run**  
   ```bash
   composer start
   ```
   Visit `http://localhost:8000`.  
   Back office is at `/login` (default credentials set in `.env`).
 
---

## License

This project is open‑source under the **MIT License**. See [LICENSE](LICENSE) for details.
 