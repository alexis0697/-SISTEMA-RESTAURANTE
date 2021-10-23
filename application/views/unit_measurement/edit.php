<div class="container container-small">
   <div class="row" style="margin-top:100px;">
      <a class="btn btn-default float-right" href="#" onclick="history.back(-1)" style="margin-bottom:10px;">
         <i class="fa fa-arrow-left"></i> <?= label("Back"); ?></a>
      <?php echo form_open_multipart('unit_measurements/edit/' . $unit_measurement->id); ?>
      <div class="form-group">
         <label for="UnitName"><?= label("Nombre"); ?></label>
         <input type="text" maxlength="50" name="name" value="<?= $unit_measurement->name; ?>" class="form-control" id="UnitName" placeholder="<?= label("Nombre"); ?>" required>
      </div>
      <div class="form-group">
         <label for="UnitAbreviatura"><?= label("Abreviatura"); ?></label>
         <input type="text" maxlength="50" name="abbreviation" value="<?= $unit_measurement->abbreviation; ?>" class="form-control" id="UnitAbreviatura" placeholder="<?= label("Abreviatura"); ?>" required>
      </div>
      <div class="form-group">
         <label for="UnitADescription"><?= label("Descripción"); ?></label>
         <input type="text" maxlength="255" name="description" value="<?= $unit_measurement->description; ?>" class="form-control" id="UnitADescription" placeholder="<?= label("Descripción"); ?>">
      </div>
      <div class="form-group">
         <button type="submit" class="btn btn-add"><?= label("Submit"); ?></button>
      </div>
      <?php echo form_close(); ?>
   </div>
</div>