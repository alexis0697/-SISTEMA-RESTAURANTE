<div class="container container-small">
   <div class="row" style="margin-top:100px;">
      <?php echo form_open_multipart('customers/edit/' . $customer->id); ?>
      <div class="col-md-12 text-center" style="padding: 0;">
         <div class="form-group">
            <a class="btn btn-info" href="#" onclick="history.back(-1)">
               <i class="fa fa-arrow-left"></i> <?= label("Back"); ?>
            </a>
            <button type="submit" class="btn btn-primary"><?= label("Submit"); ?></button>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerType"><?= label("Tipo de Cliente"); ?></label>
               <select class="form-control" name="typecustomer" id="CustomerType" required>
                  <option value="1" <?= $customer->typecustomer === 1 ? 'selected' : ''; ?>>Jurídico</option>
                  <option value="2" <?= $customer->typecustomer === 2 ? 'selected' : ''; ?>>Natural</option>
               </select>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
              <label for="CustomerNit"><?= label("NIT"); ?></label>
              <input required type="text"  value="<?= $customer->nit; ?>" maxlength="20" name="nit" class="form-control" id="CustomerNit" placeholder="<?= label("NIT"); ?>">
            </div>
          </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerTypeDocument"><?= label("CustomerTypeDocument"); ?></label>
               <select class="form-control" name="typedocument" id="CustomerTypeDocument" required>
                  <option value="1" <?= $customer->typedocument === 1 ? 'selected' : ''; ?>>DUI</option>
                  <option value="2" <?= $customer->typedocument === 2 ? 'selected' : ''; ?>>CARNET EXTRANJERÍA</option>
                  <option value="3" <?= $customer->typedocument === 3 ? 'selected' : ''; ?>>PASAPORTE</option>
               </select>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerDocument"><?= label("CustomerNumberDocument"); ?></label>
               <input required type="number" value="<?= $customer->document; ?>" name="document" maxlength="30" class="form-control" id="CustomerDocument" placeholder="<?= label("CustomerNumberDocument"); ?>">
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerName"><?= label("CustomerName"); ?></label>
               <input type="text" maxlength="50" Required name="name" value="<?= $customer->name; ?>" class="form-control" id="CustomerName" placeholder="<?= label("CustomerName"); ?>">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerLastName"><?= label("CustomerLastName"); ?></label>
               <input type="text" name="lastname" maxlength="150" Required value="<?= $customer->lastname; ?>" class="form-control" id="CustomerLastName" placeholder="<?= label("CustomerLastName"); ?>">
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerPhone"><?= label("CustomerPhone"); ?></label>
               <input type="text" name="phone" maxlength="30" value="<?= $customer->phone; ?>" class="form-control" id="CustomerPhone" placeholder="<?= label("CustomerPhone"); ?>">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerEmail"><?= label("CustomerEmail"); ?></label>
               <input type="email" maxlength="50" name="email" value="<?= $customer->email; ?>" class="form-control" id="CustomerEmail" placeholder="<?= label("CustomerEmail"); ?>">
            </div>
         </div>
      </div>



      <div class="row">
         <div class="col-md-12">
            <div class="form-group">
               <label for="CustomerDiscount"><?= label("CustomerAddress"); ?></label>
               <textarea name="discount" class="form-control" id="CustomerDiscount" id="exampleFormControlTextarea1" rows="3" placeholder="<?= label("CustomerAddress"); ?>"><?= $customer->discount; ?></textarea>
            </div>
         </div>
      </div>
   </div>
   <?php echo form_close(); ?>
</div>