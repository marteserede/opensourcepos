INSERT INTO `ospos_app_config` (`key`, `value`) VALUES 
('barcode_content', 'id'),
('barcode_first_row', 'category'),
('barcode_second_row', 'item_code'),
('barcode_third_row', 'cost_price'),
('barcode_num_in_row', '2'),
('barcode_page_width', '100'),      
('barcode_page_cellspacing', '20');

INSERT INTO `ospos_permissions` (permission_id, module_id, location_id) 
(SELECT CONCAT('sales_', location_name), 'sales', location_id FROM ospos_stock_locations);

INSERT INTO `ospos_permissions` (permission_id, module_id, location_id)
(SELECT CONCAT('receivings_', location_name), 'receivings', location_id FROM ospos_stock_locations);

-- add item_pic column to items table
ALTER TABLE `ospos_items` 
   ADD COLUMN `item_pic` int(10) DEFAULT NULL;

ALTER TABLE `ospos_people` 
   ADD COLUMN `gender` int(1) DEFAULT NULL;

