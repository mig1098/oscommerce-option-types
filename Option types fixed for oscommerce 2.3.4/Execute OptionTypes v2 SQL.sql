# osCommerce, Open Source E-Commerce Solutions
# http://www.oscommerce.com
#
# Database Changes for Option Types v2
#
# created by AvanOsch for http://Shop.CrystalCopy.nl
#
# Released under the GNU General Public License

# Add Option Types configuration menu in Admin
INSERT INTO `configuration_group` VALUES ('', 'Option Types', 'Configure Option Types and Upload settings.', '17', '1');
UPDATE `configuration_group` SET sort_order = last_insert_id() WHERE `configuration_group_id` = last_insert_id();
INSERT INTO configuration VALUES ('', 'Use Progress Bars?', 'OPTIONS_TYPE_PROGRESS', 'Both', 'Set to use the Progress bar for Text Options<br>None = No Progress Bars<br>Text = Textfields only<br>TextArea = TextAreas only<br>Both = Both Text Fields and Areas', last_insert_id(), '4', now(), now(), NULL, 'tep_cfg_select_option(array(\'None\', \'Text\', \'TextArea\', \'Both\'),'),
                                 ('', 'Upload File Prefix', 'OPTIONS_TYPE_FILEPREFIX', 'Database', 'The prefix that is used to generate unique filenames for uploads.<br>Database = insert id from database<br>Date = the upload Date<br>Time = the upload Time<br>DateTime = Upload Date and Time', last_insert_id(), '5', now(), now(), NULL, 'tep_cfg_select_option(array(\'Database\', \'Date\', \'Time\', \'DateTime\'),'),
                                 ('', 'Delete Uploads older than', 'OPTIONS_TYPE_PURGETIME', '-2 weeks', 'Uploads in the Temporary folder are automatically deleted when older than this setting.<br>Usage: -2 weeks/-5 days/-1 year/etc.', last_insert_id(), '6', now(), now(), NULL, NULL),
                                 ('', 'Upload Directory', 'UPL_DIR', 'images/uploads/', 'The directory to store uploads from registered customers.', last_insert_id(), '7', now(), now(), NULL, NULL),
                                 ('', 'Temporary Directory', 'TMP_DIR', 'images/temp/', 'The directory to store temporary uploads (from guests) which is automatically cleaned.', last_insert_id(), '8', now(), now(), NULL, NULL),
                                 ('', 'Option Type Image - Images Directory', 'OPTIONS_TYPE_IMAGEDIR', 'images/options/', 'What directory to look for Option Type Images.<br>This is where the Images should be stored.', last_insert_id(), '9', now(), now(), NULL, NULL),
                                 ('', 'Option Type Image - Images Prefix', 'OPTIONS_TYPE_IMAGEPREFIX', 'Option_', 'What prefix to use when looking for Option Type Images.<br>This is what the Image\'s name should begin with.', last_insert_id(), '10', now(), now(), NULL, NULL),
                                 ('', 'Option Type Image - Images Name', 'OPTIONS_TYPE_IMAGENAME', 'Name', 'What Option Value item to use as Name for the Option Type Images.<br>When set to "Name", the images should be named: "PREFIX"-"Option value name"-"LanguageID".jpg (Option_RedShirt_1.jpg)<br>When set to "ID", the images should be named: "PREFIX"-"Option value ID"-"LanguageID".jpg (Option_5_1.jpg)', last_insert_id(), '11', now(), now(), NULL, 'tep_cfg_select_option(array(\'Name\', \'ID\'),'),
                                 ('', 'Option Type Image - Use Language ID', 'OPTIONS_TYPE_IMAGELANG', 'Yes', 'Use language ID in Option Type Images Names?<br>This is only needed if different images are used per Language (images with text for example).', last_insert_id(), '12', now(), now(), NULL, 'tep_cfg_select_option(array(\'Yes\', \'No\'),');


# Add products_options type, length (txt fields), and comment to options
ALTER TABLE products_options
  ADD products_options_type INT( 2 ) NOT NULL ,
  ADD products_options_length INT( 2 ) DEFAULT '32' NOT NULL ,
  ADD products_options_order VARCHAR( 32 ) DEFAULT '1' NOT NULL ,
  ADD products_options_comment VARCHAR( 32 ) AFTER products_options_name;

# Add Attributes Sort Order to the basket attributes table
ALTER TABLE products_attributes
  ADD products_options_sort_order INT( 2 ) DEFAULT '1' NOT NULL;

# Add option value text to the basket attributes table
ALTER TABLE customers_basket_attributes
  ADD products_options_value_text VARCHAR( 32 ),
  CHANGE `products_options_id` `products_options_id` VARCHAR( 32 ) NOT NULL;

# Following Table is used to store Uploaded files (and generate unique file-ID's) -- This is to track Customer Uploads
CREATE TABLE `files_uploaded` (
`files_uploaded_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`sesskey` VARCHAR( 32 ) ,
`customers_id` INT( 11 ) ,
`files_uploaded_name` VARCHAR( 64 ) NOT NULL ,
`date` VARCHAR( 32 ) ,
PRIMARY KEY ( `files_uploaded_id` )
) COMMENT = 'Must always have either a sesskey or customers_id';
