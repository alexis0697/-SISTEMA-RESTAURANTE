<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stats extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (! $this->user) {
            redirect('login');
        }
        if ($this->user->role !== "admin") {
            redirect('');
        }
    }

    public function index()
    {
        try {
            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d");
            $year = date("Y");
            $TodaySales = Sale::find_by_sql("select sum(total) AS sum FROM zarest_sales where DATE(created_at) = '$date' AND canceled = 0");
            $TodayExpences = Expence::find_by_sql("select sum(amount) AS sum FROM zarest_expences where date = '2021-07-10'");
            $Top5product = Sale_item::find_by_sql("select name, product_id, sum(qt) AS totalquantity, sum(subtotal) AS totalsale FROM zarest_sale_items where canceled = 0 AND DATE_FORMAT(date, '%Y') = $year GROUP BY product_id ORDER BY SUM(qt) DESC LIMIT 10");
            $monthlySales = Sale::find_by_sql("SELECT SUM(IF(MONTH = 1, numRecords, 0)) AS 'january',SUM(IF(MONTH = 1, totaltax, 0)) AS 'januarytax',SUM(IF(MONTH = 1, totaldiscount, 0)) AS 'januarydisc',SUM(IF(MONTH = 2, numRecords, 0)) AS 'feburary',SUM(IF(MONTH = 2, totaltax, 0)) AS 'feburarytax',SUM(IF(MONTH = 2, totaldiscount, 0)) AS 'feburarydisc',SUM(IF(MONTH = 3, numRecords, 0)) AS 'march',SUM(IF(MONTH = 3, totaltax, 0)) AS 'marchtax',SUM(IF(MONTH = 3, totaldiscount, 0)) AS 'marchdisc',SUM(IF(MONTH = 4, numRecords, 0)) AS 'april',SUM(IF(MONTH = 4, totaltax, 0)) AS 'apriltax',SUM(IF(MONTH = 4, totaldiscount, 0)) AS 'aprildisc',SUM(IF(MONTH = 5, numRecords, 0)) AS 'may',SUM(IF(MONTH = 5, totaltax, 0)) AS 'maytax',SUM(IF(MONTH = 5, totaldiscount, 0)) AS 'maydisc',SUM(IF(MONTH = 6, numRecords, 0)) AS 'june',SUM(IF(MONTH = 6, totaltax, 0)) AS 'junetax',SUM(IF(MONTH = 6, totaldiscount, 0)) AS 'junedisc',SUM(IF(MONTH = 7, numRecords, 0)) AS 'july',SUM(IF(MONTH = 7, totaltax, 0)) AS 'julytax',SUM(IF(MONTH = 7, totaldiscount, 0)) AS 'julydisc',SUM(IF(MONTH = 8, numRecords, 0)) AS 'august',SUM(IF(MONTH = 8, totaltax, 0)) AS 'augusttax',SUM(IF(MONTH = 8, totaldiscount, 0)) AS 'augustdisc',SUM(IF(MONTH = 9, numRecords, 0)) AS 'september',SUM(IF(MONTH = 9, totaltax, 0)) AS 'septembertax',SUM(IF(MONTH = 9, totaldiscount, 0)) AS 'septemberdisc',SUM(IF(MONTH = 10, numRecords, 0)) AS 'october',SUM(IF(MONTH = 10, totaltax, 0)) AS 'octobertax',SUM(IF(MONTH = 10, totaldiscount, 0)) AS 'octoberdisc',SUM(IF(MONTH = 11, numRecords, 0)) AS 'november',SUM(IF(MONTH = 11, totaltax, 0)) AS 'novembertax',SUM(IF(MONTH = 11, totaldiscount, 0)) AS 'novemberdisc',SUM(IF(MONTH = 12, numRecords, 0)) AS 'december',SUM(IF(MONTH = 12, totaltax, 0)) AS 'decembertax',SUM(IF(MONTH = 12, totaldiscount, 0)) AS 'decemberdisc',SUM(numRecords) AS total, SUM(totaltax) AS totalstax, SUM(totaldiscount) AS totaldisc FROM ( SELECT id, MONTH(created_at) AS MONTH, ROUND(sum(total)) AS numRecords, ROUND(sum(taxamount)) AS totaltax, ROUND(sum(discountamount)) AS totaldiscount FROM zarest_sales WHERE canceled = 0 AND DATE_FORMAT(created_at, '%Y') = $year GROUP BY id, MONTH ) AS SubTable1");
            $monthlySalesRows = Sale::find_by_sql("SELECT T1.parameter_description AS mes,SUM(IFNULL( (SELECT SUM(total) FROM zarest_sales WHERE MONTH(created_at) = T1.parameter_code AND DATE_FORMAT( created_at, '%Y' ) = $year) , 0 )) AS ventas,SUM(IFNULL( (SELECT SUM(amount) FROM zarest_expences WHERE MONTH(date) = T1.parameter_code AND DATE_FORMAT( date, '%Y' ) = $year) , 0 )) AS gastos FROM zarest_parameters T1 WHERE T1.parameter_class = 1002  AND T1.parameter_status = 1 GROUP BY T1.parameter_description ORDER BY T1.parameter_hierarchy ASC");
            $monthlyExp = Expence::find_by_sql("SELECT SUM(IF(MONTH = 1, numRecords, 0)) AS 'january', SUM(IF(MONTH = 2, numRecords, 0)) AS 'feburary', SUM(IF(MONTH = 3, numRecords, 0)) AS 'march', SUM(IF(MONTH = 4, numRecords, 0)) AS 'april', SUM(IF(MONTH = 5, numRecords, 0)) AS 'may', SUM(IF(MONTH = 6, numRecords, 0)) AS 'june', SUM(IF(MONTH = 7, numRecords, 0)) AS 'july', SUM(IF(MONTH = 8, numRecords, 0)) AS 'august', SUM(IF(MONTH = 9, numRecords, 0)) AS 'september', SUM(IF(MONTH = 10, numRecords, 0)) AS 'october', SUM(IF(MONTH = 11, numRecords, 0)) AS 'november', SUM(IF(MONTH = 12, numRecords, 0)) AS 'december', SUM(numRecords) AS total FROM ( SELECT id, MONTH(date) AS MONTH, ROUND(sum(amount)) AS numRecords FROM zarest_expences WHERE DATE_FORMAT(date, '%Y') = $year GROUP BY id, MONTH ) AS SubTable1");
            $monthlySalesByProductsRows = Sale::find_by_sql("SELECT T1.product_id, T1.`name`,T1.price,SUM(T1.qt) AS cantidad,SUM(T1.subtotal) AS total FROM zarest_sale_items T1 WHERE YEAR(T1.date) = $year AND T1.canceled = 0 GROUP BY T1.product_id, T1.`name`,T1.price ORDER BY SUM(T1.qt) DESC LIMIT 10");
            $this->view_data['customers'] = Customer::all();
            $this->view_data['Products'] = Product::all();
            $this->view_data['Stores'] = Store::all();
            $this->view_data['Warehouses'] = Warehouse::all();
            $this->view_data['monthly'] = $monthlySales;
            $this->view_data['monthlySalesRows'] = $monthlySalesRows;
            $this->view_data['monthlySalesByProductsRows'] = $monthlySalesByProductsRows;
            $this->view_data['monthlyExp'] = $monthlyExp;
            $this->view_data['year'] = $year;
            $this->view_data['Top5product'] = $Top5product;
            $this->view_data['date']=$date;
            $this->view_data['TodaySales'] = number_format((float)$TodaySales[0]->sum, $this->setting->decimals, '.', '');
            $this->view_data['TodayExpences'] = number_format((float)$TodayExpences[0]->sum, $this->setting->decimals, '.', '');
            $this->view_data['TodayBalance'] = number_format((float)$TodaySales[0]->sum - (float)$TodayExpences[0]->sum, $this->setting->decimals, '.', '');
            $this->view_data['CustomerNumber'] = Customer::count();
            ;
            $this->view_data['CategoriesNumber'] = Category::count();
            ;
            $this->view_data['ProductNumber'] = Product::count();
            ;
            $this->content_view = 'stats';
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        
    }
}
