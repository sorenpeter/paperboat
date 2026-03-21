Paperboat Portfolio
===================

A simple website for showcaseing your creative works.

The core idea is to make it easy add your works to your website.
You just upload the images of each of your creative projects to a folder and create a html-file that have any text and links you want with it.
Then Paperboat will add this to the menu and view the images of your project as a gallery view.
You can also create simpler pages for your CV or contact information.

You can see it in action on: https://darch.dk/paperboat/

Paperboat is still very much a work-in-progress, so always make a backup before trying a new version.

Installation and setup
----------------------

0. You need a webhosting with PHP and a domain
1. Download the [zip from github](https://github.com/sorenpeter/paperboat/archive/refs/heads/main.zip)
2. Upload the content of the zip to the root folder or subfolder via FTP
	- Make sure to get the hidden `.htaccess` next to `index.php` uploaded as well
3. Open `settings.ini` and add your info and set your perfered layout
4. Add your projects and pages
5. Customize the look and feel


Adding pages and projects
-------------------------

Paperboat have support for projects and pages.

- Projects are folder within the projects-folder with an html-file and images, that is renders as gallery view.
- Pages are html-files within the pages-folder

Customize the look and feel
---------------------------

Paperboat comes with some very basic layout and color scheme that you can customize by adding a `custom.css` in the assets-folder.

To help you gettings started is a `custom_template.css` you can rename or copy to `custom.css`

Besides the default layout, two more are included, called `sidebar` and `topbar`. You can activate from the `settings.ini`.

To add your logo simply upload a files called `logo.png` to the assets-folder.


Files and folder
----------------

- `.htaccess` - hidden file, that is needed for loading paperboat (do NOT edit)
- `index.php` - this is the main program (do NOT edit)
- `settings.ini` - this is where you change your settings (please do edit)

* `assets/` - all the general files goes here
	- `layout-sidebar.css`
	- `custom.css`
	- `logo.png`
	- `favicon.png`
* `pages/` - where you pages live
	- `00_home.html` - the file called `home` is the frontpage of your portfolio
	- `01_about.html` - an about page. the name of this file without the prefix numbers determens the path to the page
	- `02_contact.html` - and a contact page
* `projects/`
	- `00_myfirstproject/` - this is a project. the name of this folder without the prefix numbers determens the path to the project
		- `main.html` - this is the content of the my first project. this can be names anything as long as it ends in `.html`
		- `IMG_0001.jpg` - some image files
		- `IMG_0002.jpg` - that will be show
		- `IMG_0003.jpg` - as a gallery
	- `2016-03-15_paperboat/` - Andother project, this one have a date as a prefix
		- `main.html`
		- `boat.jpg`
		- `logo.jpg`
		- `paper.jpg`


Inside the HTML
---------------

The first `<h1>` within a html-file in the `pages` or `projects` folders determens the title of the page or project as is what is show in the navigation menu.

```html
<h1>Name of my project</h1>

<p>This is a paragraph</p>

<a href="https://example.com">This is a Link to example.com</a>

<h2>This is a sub heading</h2>

<p>And anoter paragraph</p>
```


![](projects/2026-03-02_paperboat/2026-03_paperboat/0_file_and_folders.png)
![](projects/2026-03-02_paperboat/2026-03_paperboat/1_topbar.png)
![](projects/2026-03-02_paperboat/2026-03_paperboat/2_default.png)
![](projects/2026-03-02_paperboat/2026-03_paperboat/3_sidebar.png)



Ideas for furture features
--------------------------

* Thumnails and grid view of all the projects
- Markdown support
- Admin panel for uploading and editing content
- Favicon
- Blog feature for post and rss 


Inspiration / Shoutouts
-----------------------

- Stancy
- Indexhibt
- Feber: one php-file calender
- Yellow
- Lichen-md
