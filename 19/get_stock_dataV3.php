<?php
// 設定要查詢的9檔股票代碼
$stockSymbols = ['2330', '2317', '6505', '2412', '2881', '1301', '2419', '2002', '2332'];
date_default_timezone_set('Asia/Taipei');



// 將日期轉換為台灣格式
function convertToTaiwanDateFormat($date)
{
    // 假設輸入的日期格式是 "20241220"
    // 提取西元的年、月、日
    $year = substr($date, 0, 4);  // 年（2024）
    $month = substr($date, 4, 2); // 月（12）
    $day = substr($date, 6, 2);   // 日（20）

    // 轉換為民國年份（西元年 - 1911）
    $taiwanYear = $year - 1911;

    // 返回格式為 "民國年/月/日"
    return "{$taiwanYear}/{$month}/{$day}";
}

// 連接證交所 API 並獲取資料
function fetchStockInfo($stockSymbol = [])
{
    // 台灣證交所的 API URL，這個是範例 URL，需要根據實際情況進行修改
    //$url = "https://www.twse.com.tw/exchangeReport/STOCK_DAY?response=json&stockNo=$stockSymbol&date=20241222";
    $url = "https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=";
    foreach ($stockSymbol as $symbol) {
        $url .= "tse_" . $symbol . ".tw|";
    }
    // $url = "https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_$stockSymbol.tw";
    //https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_2330.tw|tse_2317.tw|tse_6505.tw|tse_2412.tw|tse_2881.tw|tse_1301.tw|tse_2419.tw|tse_2002.tw|tse_2332.tw|
    //echo $url;



    // 使用 cURL 抓取資料
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // 解析 JSON 格式的回應
    $data = json_decode($response, true);
    //echo "<pre>";
    //echo $data['rtmessage'];    
    //print_r($data);
    //echo "</pre>";

    // 如果成功取得資料，回傳相應的股票信息
    //if (isset($data['data']) && count($data['data']) > 0)
    if (isset($data['rtmessage']) && ($data['rtmessage'] == 'OK')) {

        return [

            $data['msgArray']

        ];
    }
    return null;
}

// 取得9檔股票資料
$stocks = [];
$current_time = time();

// 設置 09:00 和 13:30 的時間戳
$start_time = strtotime("09:00:00");
$end_time = strtotime("13:30:00");

$stockData = fetchStockInfo($stockSymbols);
foreach ($stockData[0] as $data) {
    if ($data['z'] == '-') {
        if ($current_time >= $start_time && $current_time <= $end_time) {
            $price = round(floatval($data['o']), 2); // 最新價格
            $change = 0;
        } else {
            $price = round(floatval($data['z']), 2); // 最新價格
            $change = (round(floatval($data['z']), 2) - round(floatval($data['y']), 2)) / round(floatval($data['y']), 2);
        }
    } else {
        $price = round(floatval($data['z']), 2); // 最新價格   
        $change = (round(floatval($data['z']), 2) - round(floatval($data['y']), 2)) / round(floatval($data['y']), 2);
    }
    $stocks[] = [
        'symbol' => $data['c'],
        'name' => $data['n'], // 股票名稱
        'price' => $price, // 最新價格
        'change' => $change, // 漲跌幅
        'date' => convertToTaiwanDateFormat($data['d']) // 日期
    ];
}
// echo "<pre>";
// //echo $data['rtmessage'];    
// print_r($stockData[0]);
// echo "</pre>";

// if ($stockData) {
//     $stocks[] = $stockData;

// }
//}

// 回傳 JSON 格式的資料
header('Content-Type: application/json');
echo json_encode($stocks);
