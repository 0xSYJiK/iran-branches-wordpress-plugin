# Iran Branches WordPress Plugin

A simple but powerful WordPress plugin for managing and displaying a list of company branches, specifically designed for use in Iran. It allows for easy organization of branches by province and provides a clean, searchable, and user-friendly interface on the front end.

**Note:** This entire plugin was created with the assistance of Google's Gemini.

## Features

*   **Custom "Branch" Post Type:** Easily add, edit, and manage your branches.
*   **"Province" Taxonomy:** Categorize branches by province for better organization.
*   **Manual & Automatic Sorting:**
    *   Set a custom display order for both provinces and individual branches.
    *   Items without a specified order will automatically be sorted alphabetically.
*   **Multi-Number Support:**
    *   Add multiple phone numbers to a single branch, separated by commas.
    *   Each number is automatically converted to Persian numerals.
    *   All numbers are formatted as clickable `tel:` links for easy dialing on mobile devices.
*   **Easy Navigation:** Include links for Google Maps and Waze/Neshan for each branch.
*   **Search & Filter:**
    *   A live search bar allows users to instantly find branches by name, city, or any other detail.
    *   A dropdown menu lets users filter branches by province.
*   **Accordion Display:** A clean and modern accordion interface keeps the branch list tidy and easy to navigate.
*   **Simple Shortcode:** Use a single shortcode to display the entire branch system on any page or post.

## Installation

1.  **Download:** Download the plugin from the GitHub repository by clicking "Code" > "Download ZIP".
2.  **Upload to WordPress:**
    *   In your WordPress admin panel, navigate to `Plugins` > `Add New`.
    *   Click the `Upload Plugin` button at the top of the page.
    *   Select the `.zip` file you downloaded and click `Install Now`.
3.  **Activate:** Once the installation is complete, click `Activate Plugin`.

## How to Use

### 1. Add Provinces

Before adding individual branches, it's best to set up the provinces they belong to.

1.  In the WordPress admin menu, go to `Branches` > `Provinces`.
2.  Add your desired provinces, just like you would with standard post categories.
3.  **To set a custom order,** enter a number in the "Order" field. Provinces with lower numbers will appear first in the list.

### 2. Add a New Branch

1.  Navigate to `Branches` > `Add New`.
2.  **Title:** Enter the primary name of the branch (e.g., "Central Branch").
3.  **Branch Details:** In the main content area, you'll find the "Branch Details" box:
    *   **Branch Name:** A more specific name or title for the branch.
    *   **Phone Number:** Enter one or more phone numbers, separated by commas (e.g., `021-88888888, 09123456789`).
    *   **Address:** The full address of the branch.
    *   **Google Maps Link:** The full URL for the branch's location on Google Maps.
    *   **Waze/Balad Link:** The full URL for Waze, Neshan, or Balad.
4.  **Province:** In the right-hand sidebar, select the correct province for the branch.
5.  **Order:** In the "Page Attributes" box (also in the sidebar), enter a number in the "Order" field to control the branch's position within its province.

### 3. Display on Your Website

To display the branch locator on a page:

1.  Create a new page or edit an existing one (`Pages` > `Add New`).
2.  Add a `Shortcode` block to the page content.
3.  Insert the following shortcode: `[iran_branches]`
4.  Publish or update the page. The full branch list, search bar, and province filter will now be visible on the front end.

## File Structure

```
/iran-branches/
|-- iran-branches.php   # The main plugin file containing all PHP functions.
|-- /js/
|   |-- main.js         # Handles the front-end interactivity (accordion, search, etc.).
|-- /images/
|   |-- google-maps.png # Icon for Google Maps links.
|   |-- waze.png        # Icon for Waze/Neshan links.
|-- README.md           # This file.
```