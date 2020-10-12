<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <style>
  .red {
      color:red;
      font-size:12px;
  }

  .green {
      color:green;
      font-size:12px;
  }
  .buy {
      font:bold;

  }
  
  </style>
  <body>
  <h6>https://www.twse.com.tw/zh/page/trading/exchange/MI_INDEX20.html</h6>
    <h1>各類指數日成交量值</h1>


    <?php
        
       
        
        
        function get($url) {

            $url = 'http://www.twse.com.tw/exchangeReport/BFIAMU?response=json&date=20201012&_=1702490352800';
            $cookie_file = __DIR__ . "/".'cookies.txt';
            $ch = curl_init();
            //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Host: www.twse.com.tw",
                "Connection: keep-alive",
                "Cache-Control: max-age=0",
                "Upgrade-Insecure-Requests: 1",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36",
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                "Sec-Fetch-Site: none",
                "Sec-Fetch-Mode: navigate",
                "Sec-Fetch-User: ?1",
                "Sec-Fetch-Dest: document",
                "Accept-Encoding: gzip, deflate, br",
                "Accept-Language: zh-TW,zh;q=0.9,en-US;q=0.8,en;q=0.7",
                "Cookie: _ga=GA1.3.1235678433.1598417822; _gid=GA1.3.958185623.1602490345; JSESSIONID=986B1A85531F6CE6AB659CC4697CEE98",
           
                

                
            ]);

          
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
             
        
            
            $output = curl_exec($ch);


            if (0 != curl_errno($ch)) {
                echo "Error:\n" . curl_error($ch);
    
            }
            $output = gzdecode($output);
           
            curl_close($ch);


    
            return json_decode($output, true);
        }
    ?>
    <table class="table table-striped">
    <thead>
        <tr>
        <?php $save = [];
        $d;
        $lastymd ;
        for ($i=0; $i<3; $i++):?>
            <?php 
                $format = date('m-d-Y');
                #$format = '08-31-2020';
                $date = DateTime::createFromFormat('m-d-Y', $format);
                $date->modify('-'.$i.' day');
               
                $d= $date->format('Ymd');           
                $url = "https://www.twse.com.tw/exchangeReport/BFIAMU?response=json&date=${d}&_=1601177945090";
                $weekday = date('w', strtotime($date->format('Y-m-d H:i:s')));
            
                if ($weekday ==0 || $weekday == 6) {
                    continue;
                }
              
        
                $array = get($url);
               
                if (!empty($array)) {
                    $save[$date->format('Y-m-d')] = $array;
                    $lastymd = $date->format('Y-m-d');
                }
                
            ?>
        <?php endfor;?>


            <th>d</th>
            <?php foreach ($save[$lastymd]['data'] as $v): ?>
            <th scope="col" 
            ><?php echo $v[0];?></th>
            <?php endforeach; ?>
        
        
        </tr>
    </thead>
    <tbody>

    

    

       <?php
   
        $sum = [];

        $sumSave = $save;
        sort($sumSave);
        foreach ($sumSave as $sumRow) {
            
            foreach ($sumRow['data'] as $k =>$v) {
                if (!isset($sum[$k])) {
                    $sum[$k] = 0;
                }
                $sum[$k] += $v[4];
            }
        }
       ?>

        <?php foreach ($save as $key=>$myArray): ?>
            <tr>
                <th><?php echo $key;?></th>
                <?php foreach ($myArray['data'] as $k =>$v): 
                 
                    ?>
                    <td 
                    class="
                        <?php if ($v[4] > 0):?> red <?php else :?> green <?php endif;?>

                        <?php if (($sum[$k] + $v[4]) <= -20):?> buy <?php endif;?>
                    "><?php echo $v[4] . '<br>'. $sum[$k] ; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        
       
      
    </tbody>
    </table>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </body>
</html>