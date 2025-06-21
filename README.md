<strong>DESCRIPTION:</strong>  
Responsive news website with php backend that dynamically populates the site with articles from the database.  
Supports user login/registration and allows user to administrate the articles on the website if they have admin privileges.  
Requires GD library for image manipulation for the articles (dynamically stores the image on the server as well as a smaller thumbnail version).
<br><br>
<strong>SETUP STEPS:</strong>
1. Import the debate_news database in the database folder with that exact name
2. Open your php.ini file (in XAMPP, the path is usually: C:\xampp\php\php.ini)
3. Search for this line (use Ctrl+F): ;extension=gd  
4. Uncomment it by removing the semicolon ; --> extension=gd  
5. Save the file.  
6. Restart Apache from the XAMPP control panel to apply the changes.  
