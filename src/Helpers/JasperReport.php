<?php

namespace AnshulNetgen\JasperReport\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use PHPJasper\PHPJasper;

class JasperReport{
    /**
     * Make a new report from api
     *
     * @param string $format pdf|docx|xlsx
     * @param string $api
     * @param string $tempJsonFile
     * @return mixed
     */
    public static function make($format, $api, $tempJsonFile = 'mytemp.json')
    {
        $client = new Client([
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        
        $response = $client->get($api);

        $json = $response->getBody()->getContents();

        self::createJsonFile($json, $tempJsonFile);

        self::process($format, $tempJsonFile);
    }

    /**
     * Make a new report from json
     *
     * @param string $format pdf|docx|xlsx
     * @param string $json
     * @param string $tempJsonFile
     * @return mixed
     */
    public static function makeFromJson($format, $json, $tempJsonFile = 'mytemp.json')
    {
        self::createJsonFile($json, $tempJsonFile);

        self::process($format, $tempJsonFile);
    }

    /**
     * Create Temp JSON file
     *
     * @param string $json
     * @param string $tempJsonFile
     * @return void
     */
    public static function createJsonFile($json, $tempJsonFile)
    {
        self::deleteJsonFile($tempJsonFile);
        
        Storage::disk('public')->put($tempJsonFile, $json);
    }

    /**
     * Delete JSON file
     *
     * @param string $tempJsonFile
     * @return void
     */
    public static function deleteJsonFile($tempJsonFile)
    {
        if(Storage::exists('public/' . $tempJsonFile)){
            Storage::delete('public/' . $tempJsonFile);
        }
    }
    
    /**
     * Create Report
     *
     * @param string $format pdf|docx|xlsx
     * @param string $tempJsonFile
     * @return mixed
     */
    public static function process($format, $tempJsonFile) 
    {
        $input = public_path(config('jasperreport.jrxml_file_path'));
    
        $jasper = new PHPJasper;
        $jasper->compile($input)->execute();

        $extensao = $format;
        $nome = 'jasper_report';
        $filename =  $nome  . time();
        $input   = public_path(config('jasperreport.jasper_file_path'));
        $output  = public_path('reports'. $filename);
    
        $dataFile =  public_path('/storage/' . $tempJsonFile);

        $options = [
            'format' => [$format],
            'params' => [],
            'locale' => 'en',
            'db_connection' => [
                'driver' => 'json',
                'data_file' => $dataFile
            ]
        ];

        $jasper = new PHPJasper;
    
        $jasper->process(
            $input,
            $output,
            $options
        )->execute();


        $file = $output . '.' . $extensao;

        if (!file_exists($file)) {
          abort(404);
        }
        if ($extensao == 'xls') {
          header('Content-Description: Arquivo Excel');
          header('Content-Type: application/x-msexcel');
          header('Content-Disposition: attachment; filename="' . basename($file) . '');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($file));
          flush(); // Flush system output buffer
          readfile($file);
          unlink($file);
          die();
        } else if ($extensao == 'pdf') {
          return response()->file($file)->deleteFileAfterSend();
        }
        
        self::deleteJsonFile($tempJsonFile);
    }




}


