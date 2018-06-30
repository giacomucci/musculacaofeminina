<?php

$cep = substr($_GET['cep'], 0, 5)."-".substr($_GET['cep'], 5, 7);

try {

    $user = "ashop"; 
    $pass = "fjqvho/61Wmj";
    $pdo = new PDO('mysql:host=mysql.mucci.local;dbname=ashop_database4', $user, $pass);
    
    foreach($pdo->query() as $row){
        echo $row;
?>
    var resultadoCEP = 
    { 
        'uf' : "<?php echo utf8_encode($row["uf"]); ?>", 
        'cidade' : "<?php echo utf8_encode($row["cidade"]); ?>", 
        'bairro' : "<?php echo utf8_encode($row["bairro"]); ?>", 
        'tipo_logradouro' : "", 
        'logradouro' : "<?php echo utf8_encode($row["logradouro"]); ?>",
        'resultado' : "1", 
        'resultado_txt' : "sucesso - cep completo" 
    }

<?php

    }
        $pdo = null;
    } catch(PDOException $e){

        print "Error!: " . $e->getMessage() . "<br/>";
        die();   
    }
?>