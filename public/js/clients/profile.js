document.addEventListener('DOMContentLoaded', function () {
  const editBtn = document.getElementById('profile-edit-btn');
  const cancelBtn = document.getElementById('profile-cancel-btn');
  const actions = document.getElementById('profile-actions');
  const form = document.getElementById('profile-form');
  const editableFields = Array.from(document.querySelectorAll('#profile-form input[name], #profile-form select[name]'));

  const initialValues = Object.fromEntries(
    editableFields.map((field) => [field.name, field.value])
  );

  function setEditing(isEditing) {
    editableFields.forEach((field) => {
      field.disabled = !isEditing;
    });
    actions.hidden = !isEditing;
    editBtn.hidden = isEditing;
    form.classList.toggle('is-editing', isEditing);
  }

  editBtn.addEventListener('click', function () {
    setEditing(true);
  });

  cancelBtn.addEventListener('click', function () {
    editableFields.forEach((field) => {
      field.value = initialValues[field.name] ?? '';
    });
    setEditing(false);
  });
});
