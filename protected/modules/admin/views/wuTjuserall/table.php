
<table class="content_list" style="margin-top: 20px;">

        <tr class="tb_header">
            <?php if($_SESSION['admingroupid']==10){?>
            <th width="10%">日期</th>
            <th width="10%">新增用户</th>
            <th width="10%">活跃用户</th>
            <th width="10%">付费用户</th>
            <th width="10%">新增付费用户</th>
            <th width="10%">付费金额</th>
            <th  width="10%">付费率</th>
            <th width="4%">ARPU</th>
            <th width="4%">昨日</th>
            <th width="4%">7天前</th>
            <th width="4%">30天前</th>
            <?php }elseif($_SESSION['admingroupid']==9){?>
                <th width="10%">日期</th>
                <th  width="10%">付费率</th>
                <th width="4%">ARPU</th>
                <th width="4%">昨日</th>
                <th width="4%">7天前</th>
                <th width="4%">30天前</th>
            <?php }?>
        </tr>
        <?php foreach ($this->_dayupsum['tablelist'] as $key=> $row): ?>
            <tr class="tb_list">
                <?php if($_SESSION['admingroupid']==10){?>
                <td>
                    <?php if($res==1){?>
                        <?php echo  $key?>:00
                    <?php }else{?>
                        <?php echo  $row['dateall']?>
                    <?php }?>
                </td>
                <td><?php echo $row['adduser']?></td>
                <td><?php echo $row['openuser']?></td>
                <td><?php echo $row['payuser']?></td>
                <td><?php echo $row['newpayuser']?></td>
                <td><?php echo $row['paymoney']?></td>
                <td  ><?php echo $row['fflv'] ?></td>
                <td ><?php echo $row['arpu']?></td>
                <td ><?php echo $row['zarpu']?></td>
                <td  ><?php echo $row['sarpu']?></td>
                <td  > <?php echo $row['marpu']?></td>
                <?php }elseif($_SESSION['admingroupid']==9){?>
                    <td>
                        <?php if($res==1){?>
                            <?php echo  $key?>:00
                        <?php }else{?>
                            <?php echo  $row['dateall']?>
                        <?php }?>
                    </td>
                    <td  ><?php echo $row['fflv'] ?></td>
                    <td ><?php echo $row['arpu']?></td>
                    <td ><?php echo $row['zarpu']?></td>
                    <td  ><?php echo $row['sarpu']?></td>
                    <td  > <?php echo $row['marpu']?></td>
                <?php }?>
        </tr>
        <?php endforeach; ?>

    </table>