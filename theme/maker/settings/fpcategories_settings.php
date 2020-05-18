<?php 
	
	
	defined('MOODLE_INTERNAL') || die();
	
    /* Frontpage Featured Blocks */	
	$page = new admin_settingpage('theme_maker_categories', get_string('categoriesheading', 'theme_maker'));
	$page->add(new admin_setting_heading('theme_maker_categoriesheadingsub', get_string('categoriesheadingsub', 'theme_maker'),
            format_text(get_string('categoriesheadingsubdesc' , 'theme_maker'), FORMAT_MARKDOWN)));
            
            
	//Enable Categories Section
    $name = 'theme_maker/usecategories';
    $title = get_string('usecategories', 'theme_maker');
    $description = get_string('usecategoriesdesc', 'theme_maker');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    // Categories Section Title
    $name = 'theme_maker/categoriessectiontitle';
    $title = get_string('categoriessectiontitle', 'theme_maker');
    $description = get_string('categoriessectiontitledesc', 'theme_maker');
    $default = 'Top Categories';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    

	/* Category 1 */	
    $name = 'theme_maker/category1info';
    $heading = get_string('category1info', 'theme_maker');
    $information = get_string('category1desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
	
	$name = 'theme_maker/category1title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = 'Category One';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category1image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category1image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    
    $name = 'theme_maker/category1content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    
    $name = 'theme_maker/category1url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '#link1';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    

    
    /* Category 2 */    
    $name = 'theme_maker/category2info';
    $heading = get_string('category2info', 'theme_maker');
    $information = get_string('category2desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);   
    
	$name = 'theme_maker/category2title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = 'Category Two';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category2image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category2image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category2content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category2url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '#link2';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    
    /* Category 3 */        
    $name = 'theme_maker/category3info';
    $heading = get_string('category3info', 'theme_maker');
    $information = get_string('category3desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
    
	$name = 'theme_maker/category3title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = 'Category Three';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category3image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category3image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category3content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category3url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '#link3';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    

    /* Category 4 */    
    $name = 'theme_maker/category4info';
    $heading = get_string('category4info', 'theme_maker');
    $information = get_string('category4desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
    
	$name = 'theme_maker/category4title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = 'Category Four';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category4image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category4image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category4content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category4url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '#link4';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    
    /* Category 5 */    
    $name = 'theme_maker/category5info';
    $heading = get_string('category5info', 'theme_maker');
    $information = get_string('category5desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
    
	$name = 'theme_maker/category5title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = 'Category Five';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category5image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category5image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category5content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category5url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '#link5';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);    
    
    
    /* Category 6 */   
    $name = 'theme_maker/category6info';
    $heading = get_string('category6info', 'theme_maker');
    $information = get_string('category6desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
     
	$name = 'theme_maker/category6title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = 'Category Six';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category6image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category6image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category6content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category6url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '#link6';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);    
    
    
    /* Category 7 */   
    $name = 'theme_maker/category7info';
    $heading = get_string('category7info', 'theme_maker');
    $information = get_string('category7desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
     
	$name = 'theme_maker/category7title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = 'Category Seven';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category7image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category7image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category7content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category7url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '#link7';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);    
    
    
    /* Category 8 */ 
    $name = 'theme_maker/category8info';
    $heading = get_string('category8info', 'theme_maker');
    $information = get_string('category8desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category8title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = 'Category Eight';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category8image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category8image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category8content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category8url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '#link8';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);    
    
    
    
     /* Category 9 */ 
     $name = 'theme_maker/category9info';
    $heading = get_string('category9info', 'theme_maker');
    $information = get_string('category9desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category9title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category9image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category9image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category9content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category9url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);    
    
     
     /* Category 10 */ 
     $name = 'theme_maker/category10info';
    $heading = get_string('category10info', 'theme_maker');
    $information = get_string('category10desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category10title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category10image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category10image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category10content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category10url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);    
    
     
     /* Category 11 */
     $name = 'theme_maker/category11info';
    $heading = get_string('category11info', 'theme_maker');
    $information = get_string('category11desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category11title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category11image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category11image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category11content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category11url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);    
    
     
    /* Category 12 */
    $name = 'theme_maker/category12info';
    $heading = get_string('category12info', 'theme_maker');
    $information = get_string('category12desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category12title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category12image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category12image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category12content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category12url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);    
    
    /* Category 13 */
    $name = 'theme_maker/category13info';
    $heading = get_string('category13info', 'theme_maker');
    $information = get_string('category13desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category13title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category13image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category13image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category13content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category13url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  
    
    /* Category 14 */
    $name = 'theme_maker/category14info';
    $heading = get_string('category14info', 'theme_maker');
    $information = get_string('category14desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category14title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category14image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category14image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category14content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category14url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting); 
    
    
    /* Category 15 */
    $name = 'theme_maker/category15info';
    $heading = get_string('category15info', 'theme_maker');
    $information = get_string('category15desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category15title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category15image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category15image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category15content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category15url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  
    
    
    /* Category 16 */
    $name = 'theme_maker/category16info';
    $heading = get_string('category16info', 'theme_maker');
    $information = get_string('category16desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category16title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category16image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category16image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category16content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category16url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  
    
    /* Category 17 */
    $name = 'theme_maker/category17info';
    $heading = get_string('category17info', 'theme_maker');
    $information = get_string('category17desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category17title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category17image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category17image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category17content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category17url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  
    
    /* Category 18 */
    $name = 'theme_maker/category18info';
    $heading = get_string('category18info', 'theme_maker');
    $information = get_string('category18desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category18title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category18image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category18image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category18content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category18url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  
    
    /* Category 19 */
    $name = 'theme_maker/category19info';
    $heading = get_string('category19info', 'theme_maker');
    $information = get_string('category19desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category19title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category19image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category19image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category19content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category19url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  
    
    
    /* Category 20 */
    $name = 'theme_maker/category20info';
    $heading = get_string('category20info', 'theme_maker');
    $information = get_string('category20desc', 'theme_maker');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);
       
	$name = 'theme_maker/category20title';
    $title = get_string('categorytitle', 'theme_maker');
    $description = get_string('categorytitledesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category20image';
    $title = get_string('categoryimage', 'theme_maker');
    $description = get_string('categoryimagedesc', 'theme_maker');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'category20image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category20content';
    $title = get_string('categorycontent', 'theme_maker');
    $description = get_string('categorycontentdesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_maker/category20url';
    $title = get_string('categoryurl', 'theme_maker');
    $description = get_string('categorybuttonurldesc', 'theme_maker');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting); 

    
    // Add the page
    $settings->add($page);