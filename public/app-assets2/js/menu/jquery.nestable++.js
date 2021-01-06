/*jslint browser: true, devel: true, white: true, eqeq: true, plusplus: true, sloppy: true, vars: true*/
/*global $j1_11_3 */

/*************** General ***************/

var updateOutput = function (e) {
  var list = e.length ? e : $j1_11_3(e.target),
      output = list.data('output');
  if (window.JSON) {
    if (output) {
      output.val(window.JSON.stringify(list.nestable('serialize')));
    }
  } else {
    alert('JSON browser support required for this page.');
  }
};

var nestableList = $j1_11_3(".dd.nestable > .dd-list");

/***************************************/


/*************** Delete ***************/

var deleteFromMenuHelper = function (target) {
  if (target.data('new') == 1) {
    // if it's not yet saved in the database, just remove it from DOM
    target.fadeOut(function () {
      target.remove();
      updateOutput($j1_11_3('.dd.nestable').data('output', $j1_11_3('#json-output')));
    });
  } else {
    // otherwise hide and mark it for deletion
    target.appendTo(nestableList); // if children, move to the top level
    target.data('deleted', '1');
    target.fadeOut();
  }
};

var deleteFromMenu = function () {
  var targetId = $j1_11_3(this).data('owner-id');
  var target = $j1_11_3('[data-id="' + targetId + '"]');

  var result = confirm("Delete " + target.data('name') + " and all its subitems ?");
  if (!result) {
    return;
  }

  // Remove children (if any)
  target.find("li").each(function () {
    deleteFromMenuHelper($j1_11_3(this));
  });

  // Remove parent
  deleteFromMenuHelper(target);

  // update JSON
  updateOutput($j1_11_3('.dd.nestable').data('output', $j1_11_3('#json-output')));
};

/***************************************/


/*************** Edit ***************/

var menuEditor = $j1_11_3("#menu-editor");
var editButton = $j1_11_3("#editButton");
var editInputName = $j1_11_3("#editInputName");
var editInputSlug = $j1_11_3("#editInputSlug");
var currentEditName = $j1_11_3("#currentEditName");

// Prepares and shows the Edit Form
var prepareEdit = function () {
  var targetId = $j1_11_3(this).data('owner-id');
  var target = $j1_11_3('[data-id="' + targetId + '"]');

  editInputName.val(target.data("name"));
  editInputSlug.val(target.data("slug"));
  currentEditName.html(target.data("name"));
  editButton.data("owner-id", target.data("id"));

  console.log("[INFO] Editing Menu Item " + editButton.data("owner-id"));

  menuEditor.fadeIn();
};

// Edits the Menu item and hides the Edit Form
var editMenuItem = function () {
  var targetId = $j1_11_3(this).data('owner-id');
  var target = $j1_11_3('[data-id="' + targetId + '"]');

  var newName = editInputName.val();
  var newSlug = editInputSlug.val();

  target.data("name", newName);
  target.data("slug", newSlug);

  target.find("> .dd-handle").html(newName);

  menuEditor.fadeOut();

  // update JSON
  updateOutput($j1_11_3('.dd.nestable').data('output', $j1_11_3('#json-output')));
};

/***************************************/


/*************** Add ***************/

var newIdCount = 1;

var addToMenu = function () {
  var newName = $j1_11_3("#addInputName").val();
  var newSlug = $j1_11_3("#addInputSlug").val();
  var newId = 'new-' + newIdCount;

  nestableList.append(
    '<li class="dd-item" ' +
    'data-id="' + newId + '" ' +
    'data-name="' + newName + '" ' +
    'data-slug="' + newSlug + '" ' +
    'data-new="1" ' +
    'data-deleted="0">' +
    '<div class="dd-handle">' + newName + '</div> ' +
    '<span class="button-delete btn btn-default btn-xs pull-right" ' +
    'data-owner-id="' + newId + '"> ' +
    '<i class="fa fa-times-circle-o" aria-hidden="true"></i> ' +
    '</span>' +
    '<span class="button-edit btn btn-default btn-xs pull-right" ' +
    'data-owner-id="' + newId + '">' +
    '<i class="fa fa-pencil" aria-hidden="true"></i>' +
    '</span>' +
    '</li>'
  );

  newIdCount++;

  // update JSON
  updateOutput($j1_11_3('.dd.nestable').data('output', $j1_11_3('#json-output')));

  // set events
  $j1_11_3(".dd.nestable .button-delete").on("click", deleteFromMenu);
  $j1_11_3(".dd.nestable .button-edit").on("click", prepareEdit);
};



/***************************************/



$j1_11_3(function () {

  // output initial serialised data
  updateOutput($j1_11_3('.dd.nestable').data('output', $j1_11_3('#json-output')));

  // set onclick events
  editButton.on("click", editMenuItem);

  $j1_11_3(".dd.nestable .button-delete").on("click", deleteFromMenu);

  $j1_11_3(".dd.nestable .button-edit").on("click", prepareEdit);

  $j1_11_3("#menu-editor").submit(function (e) {
    e.preventDefault();
  });

  $j1_11_3("#menu-add").submit(function (e) {
    e.preventDefault();
    addToMenu();
  });

});
