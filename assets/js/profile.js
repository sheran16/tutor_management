//  soft delete forms 
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form.js-confirm').forEach(form => {
    form.addEventListener('submit', (e) => {
      const msg = form.getAttribute('data-confirm') || 'Are you sure?';
      if (!confirm(msg)) e.preventDefault();
    });
  });
});



