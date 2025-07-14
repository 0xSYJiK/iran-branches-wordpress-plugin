
# 🇮🇷 Iran Branches WordPress Plugin 🇮🇷

<p align="center">
  <strong>English</strong> | <a href="README.md">فارسی</a>
</p>

This is a cool and handy plugin for managing and displaying a list of your company's branches! 🏢 It's specially designed for use in Iran and is super easy to work with. You can categorize branches by province and show your users a neat, searchable list.

**Note:** This entire plugin was created with the help of Google's Gemini CLI AI assistant and by an official Wibe coder! 🤖

**Disclaimer:** Use this plugin at your own risk.

##  Live Demo

You can see a live demo of this plugin at [bonhorse.ir/branches](https://bonhorse.ir/branches/).

**Note:** The map of Iran on the left side of the demo page is not included in this plugin; it is a separate plugin.

## ✨ Features

*   **Custom "Branch" Post Type:** Easily add, edit, and manage your branches.
*   **"Province" Taxonomy:** Categorize branches by province to keep everything organized.
*   **Manual & Automatic Sorting:**
    *   Set a custom display order for both provinces and individual branches.
    *   Items without a specified order will automatically be sorted alphabetically.
*   **Multi-Number Support:**
    *   Add multiple phone numbers to a single branch (just separate them with commas).
    *   Numbers are automatically converted to Persian numerals.
    *   All numbers are formatted as clickable `tel:` links for easy dialing. 📞
*   **Easy Navigation:** Include links for Google Maps and Waze/Neshan for each branch to help everyone find you. 🗺️
*   **Search & Filter:**
    *   A live search bar to quickly find branches by name, city, etc.
    *   A dropdown menu to filter branches by province.
*   **Accordion Display:** The branch list is displayed in a clean and compact accordion style.
*   **Simple Shortcode:** Just use the `[iran_branches]` shortcode to add the entire system to any page you want.

## 🚀 Installation

1.  **Download:** Download the latest version of the plugin from the [Releases page](https://github.com/0xSYJiK/iran-branches-wordpress-plugin/releases).
2.  **Upload to WordPress:**
    *   In your WordPress admin, go to `Plugins` > `Add New`.
    *   Click the `Upload Plugin` button.
    *   Select the `.zip` file and install it.
3.  **Activate:** After installation, activate the plugin.

## 🛠️ How to Use

### 1. Add Provinces

First, add the provinces:

1.  From the WordPress menu, go to `Branches` > `Provinces`.
2.  Add your desired provinces.
3.  **For a custom order,** enter a number in the "Order" field (lower number = higher up).

### 2. Add a New Branch

1.  Go to `Branches` > `Add New`.
2.  **Title:** Enter the main name of the branch.
3.  **Branch Details:** Fill in the info in the "Branch Details" box:
    *   **Branch Name:** A more specific name for the branch.
    *   **Phone Number:** Enter numbers separated by commas (e.g., `021-88888888, 09123456789`).
    *   **Address:** The full address of the branch.
    *   **Google Maps Link:** The full Google Maps URL for the branch.
    *   **Waze/Balad Link:** The full URL for Waze, Neshan, or Balad.
4.  **Province:** Select the branch's province from the sidebar.
5.  **Order:** In the "Page Attributes" box, enter a number to set the display order of the branch within its province.

### 3. Display on Your Site

1.  Create a new page or edit an existing one.
2.  Add a `Shortcode` block.
3.  Insert this shortcode: `[iran_branches]`
4.  Publish the page, and you're all set! 🎉

## 📂 File Structure

```
/iran-branches/
|-- iran-branches.php   # The main plugin file
|-- /js/
|   |-- main.js         # JavaScript for search and accordion
|-- /images/
|   |-- google-maps.png # Google Maps icon
|   |-- waze.png        # Waze/Neshan icon
|-- README.md           # This README file
|-- README-en_US.md     # The English README file
```
