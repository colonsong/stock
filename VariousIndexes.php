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
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
    
            
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
        for ($i=0; $i<40; $i++):?>
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
                
                $save[$date->format('Y-m-d')] = $array;
                $lastymd = $date->format('Y-m-d');
              
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