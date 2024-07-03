## 🧰 module add fields in suppliers

## description

module for prestashop that adds two custom fields in the `Supplier` entity.

## comments

added logic is applied in the hooks

- `actionSupplierFormBuilderModifier`, 
- `actionAfterUpdateSupplierFormHandler`
- `actionAfterCreateSupplierFormHandle`

the module includes an override of the `Supplier` class in which two new properties are added:

- `one_field`, 
- `other_field`


the new properties are sorted to be displayed above the `is_enabled` entity for better integration

view integration [example](https://i.ibb.co/Dkv35Mp/Captura-de-pantalla-2024-06-29-a-las-14-26-09.png).


















