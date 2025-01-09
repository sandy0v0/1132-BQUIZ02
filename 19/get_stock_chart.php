<?php
// 設定要查詢的9檔股票代碼
$stockSymbols = ['2330', '2317', '6505', '2412', '2881', '1301', '2419', '2002', '2332'];


function fetchStockchart($stockSymbol=[]) {
    // 台灣證交所的 API URL，這個是範例 URL，需要根據實際情況進行修改
    //$url = "https://www.twse.com.tw/exchangeReport/STOCK_DAY?response=json&stockNo=$stockSymbol&date=20241222";
    $url = "https://mis.twse.com.tw/stock/api/getChartOhlcStatis.jsp?ex=tse&ch=t00.tw&fqy=1";

    // $url = "https://mis.twse.com.tw/stock/api/getStockInfo.jsp?ex_ch=tse_$stockSymbol.tw";
    //echo $url;


    
    // 使用 cURL 抓取資料
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    // 解析 JSON 格式的回應
    $data = json_decode($response, true);

    if(isset($data['rtmessage']) && ($data['rtmessage']=='OK'))
     {

        return [

            $data['ohlcArray']
            

        ];
    }
    return null;
}

// 將日期轉換為台灣格式
function convertToTaiwanDateFormat($date) {
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


//$chartdata = [];
//nverted_ohlcArray = [];
//foreach ($stockSymbols as $symbol) {
    //$stockData = fetchStockInfo($symbol);
    $chartdata = fetchStockchart($stockSymbols);
    $flattenedData = array_merge(...$chartdata);
// 處理 ohlcArray 資料
//ohlcArray = $chartdata['ohlcArray'];

// // 創建一個新陣列來儲存格式化後的資料


//  foreach ($chartdata as $entry) {
//     $converted_ohlcArray[] = [
//          'c' => $entry['c'], // 轉為字串
//          's' =>  $entry['s'], // 轉為字串
//          't' =>  $entry['t'], // 保持原樣
//          'ts' => $entry['ts']        // 保持原樣
//     ];
//  }

// // 用轉換後的 ohlcArray 替換原始資料中的 ohlcArray
//chartdata['ohlcArray'] = $converted_ohlcArray;


    //echo "<pre>";
    //echo $data['rtmessage'];    
    //print_r($chartdata);
    //echo "</pre>";

// 回傳 JSON 格式的資料
header('Content-Type: application/json');
echo json_encode($flattenedData);
?>