<div class="container container-small">
   <?php echo form_open_multipart('waiters/edit/' . $waiter->id); ?>
   <div class="row" style="margin-top:100px;">

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
               <label for="CustomerTypeDocument"><?= label("CustomerTypeDocument"); ?></label>
               <select class="form-control" name="typedocument" id="CustomerTypeDocument" required>
                  <option value="1" <?= $waiter->typedocument === 1 ? 'selected' : ''; ?>>DNI</option>
                  <option value="2" <?= $waiter->typedocument === 2 ? 'selected' : ''; ?>>CARNET EXTRANJERÍA</option>
                  <option value="3" <?= $waiter->typedocument === 3 ? 'selected' : ''; ?>>RUC</option>
                  <option value="4" <?= $waiter->typedocument === 4 ? 'selected' : ''; ?>>PASAPORTE</option>
               </select>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label for="CustomerDocument"><?= label("CustomerNumberDocument"); ?></label>
               <input required type="number" value="<?= $waiter->document; ?>" name="document" maxlength="30" class="form-control" id="CustomerDocument" placeholder="<?= label("CustomerNumberDocument"); ?>">
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label for="WaiterName"><?= label("WaiterName"); ?></label>
               <input type="text" maxlength="50" Required name="name" value="<?= $waiter->name; ?>" class="form-control" id="WaiterName" placeholder="<?= label("WaiterName"); ?>">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label for="WaiterLastName"><?= label("WaiterLastName"); ?></label>
               <input type="text" name="lastname" value="<?= $waiter->lastname; ?>" maxlength="150" Required class="form-control" id="WaiterLastName" placeholder="<?= label("WaiterLastName"); ?>">
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label for="WaiterPhone"><?= label("WaiterPhone"); ?></label>
               <input type="text" name="phone" maxlength="30" value="<?= $waiter->phone; ?>" class="form-control" id="WaiterPhone" placeholder="<?= label("WaiterPhone"); ?>">
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label for="WaiterEmail"><?= label("WaiterEmail"); ?></label>
               <input type="email" maxlength="50" name="email" value="<?= $waiter->email; ?>" class="form-control" id="WaiterEmail" placeholder="<?= label("WaiterEmail"); ?>">
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-md-6">
            <label for="WaiterStore"><?= label("Store"); ?></label>
            <select class="form-control" name="store_id" id="WaiterStore" Required>
               <option><?= label("Store"); ?></option>
               <?php foreach ($stores as $store) : ?>
                  <option value="<?= $store->id; ?>" <?= $waiter->store_id == $store->id ? 'selected' : ''; ?>><?= $store->name; ?></option>
               <?php endforeach; ?>
            </select>
         </div>
      </div>
   </div>
   <?php echo form_close(); ?>
</div>