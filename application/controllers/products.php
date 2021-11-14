<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->user) {
            redirect('login');
        }
    }

    public function index()
    {
        $supplier = $this->input->post('filtersupp') ? $this->input->post('filtersupp') : '99';
        $supplierF = $supplier === '99' ? '99' : 'supplier';
        $type = $this->input->post('filtertype') || $this->input->post('filtertype') === '0' ? $this->input->post('filtertype') : '99';
        $typeF = $type === '99' ? '99' : 'type';
        // echo $supplierF.' = '.$supplier. ' // ' .$typeF.' = '.$type;
        $this->view_data['products'] = Product::find('all', array('conditions' => array($supplierF . ' = ? AND ' . $typeF . ' = ?', $supplier, $type)));
        //   $this->view_data['products'] = Product::all();
        $this->view_data['supplierF'] = $supplier;
        $this->view_data['typeF'] = $type;
        $this->view_data['categories'] = Category::all();
        $this->view_data['suppliers'] = Supplier::all();
        $this->view_data['units_measurements'] = Unit_measurement::all();
        $this->content_view = 'product/view';
    }


    public function csv()
    {
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "products.csv";
        $query = "SELECT code, name, category, cost, tax, description, price FROM zarest_products";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    // create xlsx
    public function generateXlsProducts()
    {
        // load excel library
        $this->load->library('excel');
        $listInfo = Product::all();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'REPORTE DE PRODUCTOS');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(18);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:D1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Código');
        $objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Categoría');
        $objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Nombre del Producto');
        $objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Precio');

        foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
            $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
        } 
        // set Row
        $rowCount = 4;
        foreach ($listInfo as $list) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $list->code);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $list->category);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $list->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $list->price);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
            $rowCount++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Productos');
        $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('0088cc');
        $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold( true );
        $filename = "productos_" . date("YmdHis") . ".xls";
        $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $object_writer->save('php://output');
    }

    public function importcsv()
    {
        $config['upload_path'] = './files/products';
        $config['allowed_types'] = 'csv';
        $config['overwrite'] = TRUE;
        $config['max_size'] = '500';

        $this->load->library('upload', $config);
        if ($this->upload->do_upload()) {
            $data = array(
                'upload_data' => $this->upload->data()
            );
            $file = $data['upload_data']['file_name'];

            $fileopen = fopen('files/products/' . $file, "r");
            if ($fileopen) {
                while (($row = fgetcsv($fileopen, 2075, ",")) !== FALSE) {
                    $filearray[] = $row;
                }
                fclose($fileopen);
            }
            array_shift($filearray);

            $fields = array(
                'code',
                'name',
                'category',
                'cost',
                'tax',
                'description',
                'price'
            );

            $final = array();
            foreach ($filearray as $key => $value) {
                $products[] = array_combine($fields, $value);
            }

            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            foreach ($products as $prdct) {
                $data = array(
                    "code" => $prdct['code'],
                    "name" => $prdct['name'],
                    "category" => $prdct['category'],
                    "cost" => $prdct['cost'],
                    "description" => $prdct['description'],
                    "tax" => $prdct['tax'],
                    "price" => $prdct['price'],
                    "color" => 'color01',
                    "photo" => '',
                    "created_at" => $date,
                    "modified_at" => $date
                );
                Product::create($data);
            }
            unlink('./files/products/' . $file);
            redirect('products');
        }
        redirect('products');
    }


    public function edit($id = FALSE)
    {
        $this->view_data['categories'] = Category::all();
        $this->view_data['suppliers'] = Supplier::all();
        $this->view_data['units_measurements'] = Unit_measurement::all();
        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
        if ($_POST) {
            $config['upload_path'] = './files/products/';
            $config['encrypt_name'] = TRUE;
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_width'] = '1000';
            $config['max_height'] = '1000';

            $product = Product::find($id);

            $this->load->library('upload', $config);
            if ($this->upload->do_upload()) {
                $data = array(
                    'upload_data' => $this->upload->data()
                );
                if ($product->photo !== '') {
                    unlink('./files/products/' . $product->photo);
                    unlink('./files/products/' . $product->photothumb);
                }
                $this->resize($data['upload_data']['full_path'], $data['upload_data']['file_name']);
                $image = $data['upload_data']['file_name'];
                $image_thumb = $data['upload_data']['raw_name'] . '_thumb' . $data['upload_data']['file_ext'];
                $data = array(
                    "type" => $this->input->post('type'),
                    //"code" => $this->input->post('code'),
                    "name" => $this->input->post('name'),
                    "category" => $this->input->post('category'),
                    "cost" => $this->input->post('cost'),
                    "description" => $this->input->post('description'),
                    "tax" => $this->input->post('tax'),
                    "alertqt" => $this->input->post('alertqt'),
                    "price" => $this->input->post('price'),
                    "color" => $this->input->post('color'),
                    "supplier" => $this->input->post('supplier'),
                    "unit" => $this->input->post('unit'),
                    "taxmethod" => $this->input->post('taxmethod'),
                    "options" => $this->input->post('options'),
                    "photo" => $image,
                    "photothumb" => $image_thumb,
                    "created_at" => $date,
                    "modified_at" => $date
                );
                $product->update_attributes($data);
                if ($product->is_valid()) {
                    redirect("products", "refresh");
                } else {
                    $errorm = label('codeerror');
                    $this->session->set_flashdata('error', $errorm);
                    redirect("products/edit/" . $id);
                }
            } else {
                $data = array(
                    "type" => $this->input->post('type'),
                    //"code" => $this->input->post('code'),
                    "name" => $this->input->post('name'),
                    "category" => $this->input->post('category'),
                    "description" => $this->input->post('description'),
                    "alertqt" => $this->input->post('alertqt'),
                    "cost" => $this->input->post('cost'),
                    "tax" => $this->input->post('tax'),
                    "price" => $this->input->post('price'),
                    "color" => $this->input->post('color'),
                    "supplier" => $this->input->post('supplier'),
                    "unit" => $this->input->post('unit'),
                    "taxmethod" => $this->input->post('taxmethod'),
                    "options" => $this->input->post('options'),
                    "created_at" => $date,
                    "modified_at" => $date
                );
                $product->update_attributes($data);
                if ($product->is_valid()) {
                    redirect("products", "refresh");
                } else {
                    $errorm = label('codeerror');
                    $this->session->set_flashdata('error', $errorm);
                    redirect("products/edit/" . $id);
                }
            }
        } else {
            $this->view_data['product'] = Product::find($id);
            $this->content_view = 'product/edit';
        }
    }

    public function delete($id)
    {
        $products = Sale_item::find('all', array(
            'conditions' => array(
                'product_id = ?',
                $id
            )
        ));
        if (count($products) > 0) {
            $this->session->set_flashdata('error', "Producto no se puede eliminar. Ya tiene ventas realizadas");
            redirect("products", "refresh");
        } else {
            $product = Product::find($id);
            if ($product->photo !== '') {
                unlink('./files/products/' . $product->photo);
                unlink('./files/products/' . $product->photothumb);
            }
            $stock = Stock::delete_all(array('conditions' => array('product_id = ?', $id)));
            $combos = Combo_item::delete_all(array('conditions' => array('product_id = ?', $id)));
            $product->delete();
            redirect("products", "refresh");
        }
    }

    function resize($path, $file)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['create_thumb'] = TRUE;
        $config['maintain_thum'] = TRUE;
        $config['width'] = 120;
        $config['height'] = 120;
        $config['new_image'] = './files/products/' . $file;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
    }

    function updatestock()
    {
       
        $quant = $this->input->post('quant');
        $quantw = $this->input->post('quantw');
        $pricest = $this->input->post('pricest');
        $productID = $this->input->post('productID');
        $product = Sale_item::find('all', array(
            'conditions' => array(
                'product_id = ?',
                $productID
            )
        ));
        if ($quant) {
            foreach ($quant as $qt) {
                if ($item = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $qt['store_id'], $productID)))) {
                    $item->quantity = $qt['quantity'];
                    $item->save();
                } else {
                    $qt['product_id'] = $productID;
                    Stock::create($qt);
                }
            }
        }
        if ($pricest) {
            foreach ($pricest as $pr) {
                if ($item = Stock::find('first', array('conditions' => array('store_id = ? AND product_id = ?', $pr['store_id'], $productID)))) {
                    $item->price = $product->price;
                    $item->save();
                } else {
                    $pr['product_id'] = $productID;
                    Stock::create($pr);
                }
            }
        }
        if ($quantw) {
            foreach ($quantw as $qt) {
                if ($item = Stock::find('first', array('conditions' => array('warehouse_id = ? AND product_id = ?', $qt['warehouse_id'], $productID)))) {
                    $item->quantity = $qt['quantity'];
                    $item->save();
                } else {
                    $qt['product_id'] = $productID;
                    Stock::create($qt);
                }
            }
        }
    }
}
