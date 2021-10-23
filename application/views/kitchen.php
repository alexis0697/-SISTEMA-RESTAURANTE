<script type="text/javascript">
   
   getTablesOrdersDetails();
   var tableSelected;
   $(document).ready(function() {
      $('#ticket').modal({
         show: false,
         backdrop: 'static',
         keyboard: false
      });

   });
   var id = setTimeout(function() {
      window.location.reload(1);
   }, 15000);

   function showticket(table) {
      tableSelected = table;
      $('#printSection').load("<?php echo site_url('pos/showticketKit') ?>" + table);
      clearTimeout(id);
      $('#ticket').modal('show');
   }
   function getTablesOrdersDetails(){
      showOrderTable(44);
   showOrderTable(45);
   showOrderTable(46);
   showOrderTable(47);
   $.ajax({
         url: "<?php echo site_url('pos/orderDetailsTable') ?>/" + table,
         data: {
            id: id
         },
         type: "POST",
         success: function(data) {
            $('#showOrderTableContainer' + table).prepend(data);
         },
         error: function(jqXHR, textStatus, errorThrown) {
            alert("error");
         }
      });
   }
   function showOrderTable(table) {
      console.log(table)
      tableSelected = table;
      clearTimeout(id);
      $.ajax({
         url: "<?php echo site_url('pos/orderDetailsTable') ?>/" + table,
         data: {
            id: id
         },
         type: "POST",
         success: function(data) {
            $('#showOrderTableContainer' + table).prepend(data);
         },
         error: function(jqXHR, textStatus, errorThrown) {
            alert("error");
         }
      });
      //$('#ticket').modal('show');
   }

   function PrintTicket() {
      $('.modal-body').removeAttr('id');
      window.print();
      $('.modal-body').attr('id', 'modal-body');
   }

   function closeModal() {
      window.location.reload(1);
   }

   function cambiarEstadoPlato(select, id) {
      $.ajax({
         url: "<?php echo site_url('pos/changeStatusItemPos') ?>/",
         data: {
            status: select.value,
            id: id
         },
         type: "POST",
         success: function(data) {
            console.log(data);
            if (data == 1) {
               if (tableSelected != null) {
                  $('#printSection').load("<?php echo site_url('pos/showticketKit') ?>/" + tableSelected);
               }
            }
         },
         error: function(jqXHR, textStatus, errorThrown) {
            alert("error");
         }
      });
   }
</script>
<!-- Page Content -->
<div class="container" style="margin-bottom: 20px;">
   <div class="row">
      <?= !$zones ? '<h4 style="margin-top:30px">' . label("NoTables") . '</h4>' : ''; ?>
      <?php foreach ($zones as $zone) : ?>
         <div class="row">
            <h1 class="choose_store"> <?= $zone->name; ?> </h1>
            <hr>
         </div>
         <div class="row tablesrow">
            <?php foreach ($tables as $table) : ?>
               <?php if ($table->zone_id == $zone->id) { ?>
                  <?php if ($table->status == 1) { ?>
                     <div class="col-sm-2 col-xs-4 tableList tableCook nohover-item">
                        <?php if ($table->time == 'n') { ?><span class="tablenotif">.</span><?php } ?>
                        <a class="btn btn-lg kitchentable-btn enabled" href="javascript:void(0)" onclick="showticket(<?= $table->id; ?>)">
                           <?= $table->name; ?>
                           <!--<img src="<?= base_url() ?>assets/img/cooking.png" alt="<?= $table->name; ?>">-->
                           <div id="showOrderTableContainer<?= $table->id; ?>" style="max-width: 5%;">
                           </div>
                        </a>
                     </div>
                  <?php } ?>
               <?php } ?>
            <?php endforeach; ?>
         </div>
      <?php endforeach; ?>


   </div>
</div>
<!-- /.container -->

<!-- <div id="footer" style="background-color: #293042;width: 100%;">
   <div class="container">
      <p class="footer-block" style="margin: 20px 0;color:#fff;"><?= label('title'); ?> - <?= $this->setting->companyname; ?>.</p>
   </div>
</div> -->

<!-- Modal ticket -->
<div class="modal fade" id="ticket" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog" role="document" id="ticketModal" style="width: 470px;">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="ticket"><?= label("Receipt"); ?></h4>
         </div>
         <div class="modal-body" id="modal-body">
            <div id="printSection">
               <!-- Ticket goes here -->
               <center>
                  <h1 style="color:#34495E"><?= label("empty"); ?></h1>
               </center>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default hiddenpr" onclick="closeModal()"><?= label("Close"); ?></button>
            <button type="button" class="btn btn-add hiddenpr" onclick="PrintTicket()"><?= label("print"); ?></button>
         </div>
      </div>
   </div>
</div>
<!-- /.Modal -->