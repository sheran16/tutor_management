(function () {
  const modal = document.getElementById('viewModal');
  const closeBtn = modal.querySelector('.modal-close');
  const backdrop = modal.querySelector('.modal-backdrop');

  // pop and data
  function openModal(data) {
    document.getElementById('m_id').textContent = data.id;
    document.getElementById('m_name').textContent = data.name;
    document.getElementById('m_address').textContent = data.address;
    document.getElementById('m_dob').textContent = data.dob;
    document.getElementById('m_grade').textContent = data.grade;
    document.getElementById('m_contact').textContent = data.contact;

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
  }

  // close modal
  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
  }

  // click on View button
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.view-btn');
    if (!btn) return;
    openModal({
      id: btn.dataset.id,
      name: btn.dataset.name,
      address: btn.dataset.address,
      dob: btn.dataset.dob,
      grade: btn.dataset.grade,
      contact: btn.dataset.contact
    });
  });

  // close events
  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
  });
})();

