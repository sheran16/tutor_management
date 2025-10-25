(function () {
  const modal = document.getElementById('updateModal');
  // pop and data
  function openModal(d) { 
    document.getElementById('u_id').value      = d.id || '';
    document.getElementById('u_name').value    = d.name || '';
    document.getElementById('u_address').value = d.address || '';
    document.getElementById('u_dob').value     = d.dob || '';
    document.getElementById('u_grade').value   = d.grade || '';    
    document.getElementById('u_contact').value = d.contact || '';
    // open, accessbility, ms
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    setTimeout(() => document.getElementById('u_name').focus(), 50);
  }

  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
  }

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.update-btn');
    if (!btn) return;
    // data 
    openModal({
      id: btn.dataset.id,
      name: btn.dataset.name,
      address: btn.dataset.address,
      dob: btn.dataset.dob,
      grade: btn.dataset.grade,
      contact: btn.dataset.contact
    });
  });

  modal?.addEventListener('click', (e) => {
    if (e.target.matches('[data-close-update]') || e.target.classList.contains('umodal-backdrop')) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
  });
})();
