/*** 
This is the menu creation code - place it right after you body tag
Feel free to add this to a stand-alone js file and link it to your page.
**/

//Menu object creation
oCMenu=new makeCM("oCMenu") //Making the menu object. Argument: menuname

oCMenu.frames = 0

//Menu properties   
oCMenu.pxBetween=2
oCMenu.fromLeft=0 
oCMenu.fromTop=91
oCMenu.rows=1
oCMenu.menuPlacement="center"
														 
oCMenu.offlineRoot="/" 
oCMenu.onlineRoot="/" 
oCMenu.resizeCheck=1 
oCMenu.wait=1000
oCMenu.zIndex=0

//Background bar properties
oCMenu.useBar=0

//Level properties - ALL properties have to be spesified in level 0
oCMenu.level[0]=new cm_makeLevel() //Add this for each new level
oCMenu.level[0].width=156
oCMenu.level[0].height=30 
oCMenu.level[0].regClass="clLevel0"
oCMenu.level[0].overClass="clLevel0over"
oCMenu.level[0].borderX=1
oCMenu.level[0].borderY=1
oCMenu.level[0].borderClass="clLevel0border"
oCMenu.level[0].offsetX=0
oCMenu.level[0].offsetY=0
oCMenu.level[0].rows=0
oCMenu.level[0].arrow=0
oCMenu.level[0].arrowWidth=0
oCMenu.level[0].arrowHeight=0
oCMenu.level[0].align="bottom"

//EXAMPLE SUB LEVEL[1] PROPERTIES - You have to specify the properties you want different from LEVEL[0] - If you want all items to look the same just remove this
oCMenu.level[1]=new cm_makeLevel() //Add this for each new level (adding one to the number)
oCMenu.level[1].width=oCMenu.level[0].width+100
oCMenu.level[1].height=30
oCMenu.level[1].regClass="clLevel1"
oCMenu.level[1].overClass="clLevel1over"
oCMenu.level[1].borderX=1
oCMenu.level[1].borderY=1
oCMenu.level[1].align="right" 
oCMenu.level[1].offsetX=-(oCMenu.level[0].width-2)/2+20
oCMenu.level[1].offsetY=0
oCMenu.level[1].borderClass="clLevel1border"

var current_page = getVar("current_page");
var sub_page = getVar("sub_page");

/******************************************
Menu item creation:
myCoolMenu.makeMenu(name, parent_name, text, link, target, width, height, regImage, overImage, regClass, overClass , align, rows, nolink, onclick, onmouseover, onmouseout) 
*************************************/
if ((current_page == "home") || (current_page == "")) {
	oCMenu.makeMenu('top0', '', 'Home', 'index.asp?current_page=home', '', '', '', '', '', "clLevel0select")
}
else {
	oCMenu.makeMenu('top0', '', 'Home', 'index.asp?current_page=home')
}

if (current_page == "outreach") {
	oCMenu.makeMenu('top1', '', 'Outreach', 'outreach/index.asp?current_page=outreach', '', '', '', '', '', "clLevel0select")
}
else {
	oCMenu.makeMenu('top1', '', 'Outreach', 'outreach/index.asp?current_page=outreach')
}

oCMenu.makeMenu('sub11', 'top1', 'Publications', 'outreach/publications/index.asp?current_page=outreach')
oCMenu.makeMenu('sub12', 'top1', 'Presentations', 'outreach/presentations/index.asp?current_page=outreach')
oCMenu.makeMenu('sub13', 'top1', 'Press Releases', 'outreach/press_releases/2012/index.asp?current_page=outreach')

/**
if (current_page == "research_units") {
	oCMenu.makeMenu('top2', '', '< Project Areas >', 'research_units/index.asp?current_page=research_units')
}
else {
	oCMenu.makeMenu('top2', '', 'Project Areas', 'research_units/index.asp?current_page=research_units')
}

oCMenu.makeMenu('sub21', 'top2', 'Markets/Policy', 'research_units/index.asp?current_page=research_units&sub_page=market_policy')
oCMenu.makeMenu('sub22', 'top2', 'Missouri Farms', 'research_units/index.asp?current_page=research_units&sub_page=mo_farms')
**/

if (current_page == "about_fapri") {
	oCMenu.makeMenu('top3', '', 'About FAPRI', 'about_fapri/index.asp?current_page=about_fapri', '', '', '', '', '', "clLevel0select")
}
else {
	oCMenu.makeMenu('top3', '', 'About FAPRI', 'about_fapri/index.asp?current_page=about_fapri')
}

oCMenu.makeMenu('sub31', 'top3', 'Research Projects', 'about_fapri/research_projects.asp?current_page=about_fapri')
oCMenu.makeMenu('sub32', 'top3', 'Products', 'about_fapri/products.asp?current_page=about_fapri')
oCMenu.makeMenu('sub33', 'top3', 'Education & Outreach', 'about_fapri/ed_outreach.asp?current_page=about_fapri')
oCMenu.makeMenu('sub34', 'top3', 'Staff Directory', 'about_fapri/staff_directory.asp?current_page=about_fapri')
oCMenu.makeMenu('sub35', 'top3', 'Employment', 'about_fapri/employment.asp?current_page=about_fapri')
oCMenu.makeMenu('sub36', 'top3', 'Links', 'about_fapri/links.asp?current_page=about_fapri')

if (current_page == "farmers_corner") {
	oCMenu.makeMenu('top4', '', 'Farmers&rsquo; Corner', 'farmers_corner/index.asp?current_page=farmers_corner', '', '', '', '', '', "clLevel0select")
}
else {
	oCMenu.makeMenu('top4', '', 'Farmers&rsquo; Corner', 'farmers_corner/index.asp?current_page=farmers_corner')
}

oCMenu.makeMenu('sub41', 'top4', 'Crop Budgets', 'farmers_corner/budgets/index.asp?current_page=farmers_corner')
oCMenu.makeMenu('sub42', 'top4', 'Software Tools', 'farmers_corner/tools/index.asp?current_page=farmers_corner')
oCMenu.makeMenu('sub43', 'top4', 'Decisive Marketing', 'farmers_corner/mktng_newsletter/index.asp?current_page=farmers_corner')
oCMenu.makeMenu('sub44', 'top4', 'Crop Report Commentary', 'farmers_corner/CropReportCommentary_Current.pdf', '_blank')
oCMenu.makeMenu('sub45', 'top4', 'Market Plans', 'farmers_corner/mrkt_plan/index.asp?current_page=farmers_corner')

if (current_page == "beginning_farmers") {
	oCMenu.makeMenu('top5', '', 'Beginning Farmer', 'beginning_farmers/index.asp?current_page=beginning_farmers', '', '', '', '', '', "clLevel0select")
}
else {
	oCMenu.makeMenu('top5', '', 'Beginning Farmer', 'beginning_farmers/index.asp?current_page=beginning_farmers')
}

oCMenu.makeMenu('sub51', 'top5', 'Projects', 'beginning_farmers/projects/index.asp?current_page=beginning_farmers')
oCMenu.makeMenu('sub52', 'top5', 'Representative Farms', 'beginning_farmers/repfarms/index.asp?current_page=beginning_farmers')
oCMenu.makeMenu('sub53', 'top5', 'Publications', 'beginning_farmers/publications/index.asp?current_page=beginning_farmers')
oCMenu.makeMenu('sub54', 'top5', 'Survey', 'beginning_farmers/survey/index.asp?current_page=beginning_farmers')
oCMenu.makeMenu('sub56', 'top5', 'Online Tools', 'projects/index.php?current_page=beginning_farmers')

//Leave this line - it constructs the menu
oCMenu.construct()

