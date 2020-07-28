<?php
include'../includes/connection.php';
include'../includes/topp.php';
$product_ids = array();

if(filter_input(INPUT_POST, 'addpos')){
    if(isset($_SESSION['pointofsale'])){
        
        $count = count($_SESSION['pointofsale']);
        
        $product_ids = array_column($_SESSION['pointofsale'], 'id');

        if (!in_array(filter_input(INPUT_GET, 'id'), $product_ids)){
        $_SESSION['pointofsale'][$count] = array
            (
                'id' => filter_input(INPUT_GET, 'id'),
                'name' => filter_input(INPUT_POST, 'name'),
                'price' => filter_input(INPUT_POST, 'price'),
                'quantity' => filter_input(INPUT_POST, 'quantity')
            );   
        }
        else { 
            for ($i = 0; $i < count($product_ids); $i++){
                if ($product_ids[$i] == filter_input(INPUT_GET, 'id')){
                    $_SESSION['pointofsale'][$i]['quantity'] += filter_input(INPUT_POST, 'quantity');
                }
            }
        }
    }
    else { 
        $_SESSION['pointofsale'][0] = array
        (
            'id' => filter_input(INPUT_GET, 'id'),
            'name' => filter_input(INPUT_POST, 'name'),
            'price' => filter_input(INPUT_POST, 'price'),
            'quantity' => filter_input(INPUT_POST, 'quantity')
        );
    }
}
if(filter_input(INPUT_GET, 'action') == 'delete'){
    foreach($_SESSION['pointofsale'] as $key => $product){
        if ($product['id'] == filter_input(INPUT_GET, 'id')){
            unset($_SESSION['pointofsale'][$key]);
        }
    }
    $_SESSION['pointofsale'] = array_values($_SESSION['pointofsale']);
}
function pre_r($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}
                ?>
<?php 
                $query = 'SELECT ID, t.TYPE
                          FROM users u
                          JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = '.$_SESSION['MEMBER_ID'].'';
                $result = mysqli_query($db, $query) or die (mysqli_error($db));
                while ($row = mysqli_fetch_assoc($result)) {
                          $Aa = $row['TYPE'];
if ($Aa=='Storage'){
             ?> <script type="text/javascript">
alert("Restricted Page! You will be redirected to Inventory");
window.location = "index.php";
</script>
<?php   }
                         
           
}   
            ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-0">
            <div class="card-header py-2">
                <h4 class="m-1 text-lg text-primary">Product category</h4>
            </div>
            <!-- /.panel-heading -->
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-target="#beef" data-toggle="tab">Beef</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-target="#chicken" data-toggle="tab">Chicken</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#seafood" data-toggle="tab">Seafood</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pork" data-toggle="tab">Pork</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#processedmeat" data-toggle="tab">Processed Meat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#others" data-toggle="tab">Others</a>
                    </li>
                </ul>

                <?php include 'postabpane.php'; ?>

                <div style="clear:both"></div>
                <br />
                <div class="card shadow mb-4 col-md-12">
                    <div class="card-header py-3 bg-white">
                        <h4 class="m-2 font-weight-bold text-primary">Withdrawal</h4>
                    </div>

                    <div class="row">
                        <div class="card-body col-md-9">
                            <div class="table-responsive">

                                <form role="form" method="post" action="pos_transac.php?action=add">
                                    <input type="hidden" name="employee" value="<?php echo $_SESSION['FIRST_NAME']; ?>">
                                    <input type="hidden" name="role" value="<?php echo $_SESSION['JOB_TITLE']; ?>">

                                    <table class="table">
                                        <tr>
                                            <th width="55%">Product Name</th>
                                            <th width="10%">Quantity</th>
                                            <th width="15%">Price</th>
                                            <th width="15%">Total</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                        <?php  

        if(!empty($_SESSION['pointofsale'])):  
            
             $total = 0;  
        
             foreach($_SESSION['pointofsale'] as $key => $product): 
        ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="name[]"
                                                    value="<?php echo $product['name']; ?>">
                                                <?php echo $product['name']; ?>
                                            </td>

                                            <td>
                                                <input type="hidden" name="quantity[]"
                                                    value="<?php echo $product['quantity']; ?>">
                                                <?php echo $product['quantity']; ?>
                                            </td>

                                            <td>
                                                <input type="hidden" name="price[]"
                                                    value="<?php echo $product['price']; ?>">
                                                ₱ <?php echo number_format($product['price']); ?>
                                            </td>

                                            <td>
                                                <input type="hidden" name="total"
                                                    value="<?php echo $product['quantity'] * $product['price']; ?>">
                                                ₱
                                                <?php echo number_format($product['quantity'] * $product['price'], 2); ?>
                                            </td>
                                            <td>
                                                <a href="pos.php?action=delete&id=<?php echo $product['id']; ?>">
                                                    <div class="btn bg-gradient-danger btn-danger"><i
                                                            class="fas fa-fw fa-trash"></i></div>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php  
                  $total = $total + ($product['quantity'] * $product['price']);
             endforeach;  
        ?>


                                        <?php  
        endif;
        ?>
                                    </table>
                            </div>
                        </div>

                        <?php
include 'posside.php';
include'../includes/footer.php';
?>