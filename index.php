<?php
date_default_timezone_set("CET");

$url = "http://www.energinet.dk/_layouts/internetelwebservice.asmx";

$xmlPost = '<Envelope xmlns="http://www.w3.org/2003/05/soap-envelope">
    <Body>
        <GetInternetElValues xmlns="http://energinet.dk/InternetElWebService"/>
    </Body>
</Envelope>';

$headers = array(
"Content-type: text/xml;charset=\"utf-8\"",
"Accept: text/xml",
"Cache-Control: no-cache",
"Pragma: no-cache",
"SOAPAction: http://energinet.dk/InternetElWebService/GetInternetElValues", 
"Content-length: ".strlen($xmlPost),
);

$cu = curl_init();
curl_setopt($cu, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($cu, CURLOPT_URL, $url);
curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cu, CURLOPT_TIMEOUT, 10);
curl_setopt($cu, CURLOPT_POST, true);
curl_setopt($cu, CURLOPT_POSTFIELDS, $xmlPost);
curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($cu);
curl_close($cu);

$data = explode("><", $response);
$output = array();
$pris = 0;

$enddate = date("d-m-Y");
$elpriser = json_decode(file_get_contents("http://www.nordpoolspot.com/api/marketdata/chart/41?currency=,EUR,EUR,EUR&endDate={$endDate}"), true)["data"]["Rows"];
foreach($elpriser as $elpris)
{
	$time = date("Y-m-d");
	$time .= "T";
	$time .= date("H:00:00");
	if($elpris["StartTime"] == $time)
	{
		// EUR pr. mWh
		$pris = ((float) str_replace(",", ".", $elpris["Columns"][0]["Value"]) + (float) str_replace(",", ".", $elpris["Columns"][1]["Value"])) /2;
	}
}

$output["title"] = "Overview of the Danish electrical grid. Usage, production, import and export.";
$output["description"] = "negative numbers denote export, positive numbers denote import.";
$output["urls"]["flash_map"] = "https://www.energinet.dk/Flash/Forside/index.html";
$output["urls"]["price_list"] = "http://www.nordpoolspot.com/Market-data1/Elspot/Area-Prices/DK/Hourly/?view=table";
$output["cost"]["eur"]["mwh"] = $pris;
$output["cost"]["dkk"]["mwh"] = $pris * 7.44;
$output["cost"]["eur"]["kwh"] = $pris / 1000;
$output["cost"]["dkk"]["kwh"] = ($pris * 7.44) / 1000;

foreach($data as $line)
{
	$d = explode(">", $line);
	$case = $d[0];
	$data = isset($d[1]) ? $d[1] : NULL;

	if(stristr($data, "</"))
	{
		$f = explode("</", $data);
		$data = $f[0];
	}

	switch($case)
	{
		case "Timestamp":
			$months = array("Januar" => "January", "Febuar" => "Febuary", "Marts" => "March", "Maj" => "May", "Juni" => "June", "Juli" => "July", "Oktober" => "October");
			foreach($months as $dansk => $month)
				$data = str_replace($dansk, $month, $data);

			$data = str_replace(".", "", $data);
			$date = new DateTime(date(DATE_RFC822, strtotime($data)));
			$timezones = array(
				"CET",
				"GMT",
				"UTC",
				"EST",
				"PST"
				);

			foreach($timezones as $timezone)
			{
				$date->setTimezone(new DateTimeZone($timezone));
				$output["timestamp"][] = $date->format(DATE_RFC822); //"Y-m-d\TH:i:s\Z");
			}
		break;

		case "Centrale_kraftvaerker":
			$output["generation"]["central_powerplants"]["kw"] = $data * 1000;
			$output["generation"]["central_powerplants"]["mw"] = $data;
			$totalGeneration = $totalGeneration + $data;
		break;

		case "Decentrale_kraftvaerker":
			$output["generation"]["decentral_powerplants"]["kw"] = $data * 1000;
			$output["generation"]["decentral_powerplants"]["mw"] = $data;
			$totalGeneration = $totalGeneration + $data;
		break;

		case "Vindmoeller":
			$output["generation"]["windmills"]["kw"] = $data * 1000;
			$output["generation"]["windmills"]["mw"] = $data;
			$totalGeneration = $totalGeneration + $data;
		break;

		case "Solcelle_Produktion":
			$output["generation"]["solar_cell"]["kw"] = $data * 1000;
			$output["generation"]["solar_cell"]["mw"] = $data;
			$totalGeneration = $totalGeneration + $data;
		break;

		case "Udveksling":
			$output["importexport"]["total_exchange"]["kw"] = $data * 1000;
			$output["importexport"]["total_exchange"]["mw"] = $data;
		break;

		case "Elforbrug":
			$output["usage"]["electric_usage"]["kw"] = $data * 1000;
			$output["usage"]["electric_usage"]["mw"] = $data;
		break;

		case "CO2":
			$output["generation"]["co2_emission"]["grams"]["kwh"] = $data;
			$output["generation"]["co2_emission"]["grams"]["mwh"] = $data / 1000;
		break;

		case "Udveksling_JyllandNorge":
			$output["importexport"]["exchange_jutland_norway"]["kw"] = $data * 1000;
			$output["importexport"]["exchange_jutland_norway"]["mw"] = $data;
		break;

		case "Udveksling_JyllandSverige":
			$output["importexport"]["exchange_jutland_sweden"]["kw"] = $data * 1000;
			$output["importexport"]["exchange_jutland_sweden"]["mw"] = $data;
		break;

		case "Udveksling_JyllandTyskland":
			$output["importexport"]["exchange_jutland_germany"]["kw"] = $data * 1000;
			$output["importexport"]["exchange_jutland_germany"]["mw"] = $data;
		break;

		case "Udveksling_SjaellandSverige":
			$output["importexport"]["exchange_zealand_sweden"]["kw"] = $data * 1000;
			$output["importexport"]["exchange_zealand_sweden"]["mw"] = $data;
		break;

		case "Udveksling_SjaellandTyskland":
			$output["importexport"]["exchange_zealand_germany"]["kw"] = $data * 1000;
			$output["importexport"]["exchange_zealand_germany"]["mw"] = $data;
		break;

		case "Udveksling_BornholmSverige":
			$output["importexport"]["exchange_bornholm_sweden"]["kw"] = $data * 1000;
			$output["importexport"]["exchange_bornholm_sweden"]["mw"] = $data;
		break;

		case "Udveksling_FynSjaelland":
			$output["importexport"]["exchange_funen_zealand"]["kw"] = $data * 1000;
			$output["importexport"]["exchange_funen_zealand"]["mw"] = $data;
		break;
	}
}

// Total generation
$output["generation"]["total_generation"]["mw"] = $totalGeneration;
$output["generation"]["total_generation"]["kw"] = $totalGeneration * 1000;
$output["generation"]["total_generation"]["co2"]["grams"] = ($totalGeneration * $output["generation"]["co2_emission"]["grams"]["mwh"]);
$output["generation"]["total_generation"]["co2"]["kilos"] = ($totalGeneration * $output["generation"]["co2_emission"]["grams"]["mwh"]) / 1000;

// Output the data
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("etag: \"". md5(serialize($output)) . "\"");
header("Content-Type: application/json; charset=utf-8");
echo json_encode($output, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);