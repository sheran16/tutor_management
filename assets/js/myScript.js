document.addEventListener('DOMContentLoaded', () => {
    const addRowBtn = document.getElementById('addRowBtn');
    const addForm = document.getElementById('addStudentForm');
    const popup = document.getElementById('popupMessage');

    
    if (addRowBtn && addForm) {
        addRowBtn.addEventListener('click', () => {
            if (addForm.style.display === 'none' || addForm.style.display === '') {
                addForm.style.display = 'block';
                
                hideAllForms();
                addForm.style.display = 'block';
            } else {
                addForm.style.display = 'none';
            }
        });
    }

    
    if (popup && popup.innerText.trim() !== '') {
        popup.style.display = 'block';

        
        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);
    }

   
    document.addEventListener('click', function(event) {
    const deleteModal = document.getElementById('deleteModal');
        
        
        if (deleteModal && deleteModal.style.display === 'flex') {
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
    });

    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const deleteModal = document.getElementById('deleteModal');
            
            
            if (deleteModal && deleteModal.style.display === 'flex') {
                closeDeleteModal();
            }
        }
    });
});


function hideAllForms() {
    const addForm = document.getElementById('addStudentForm');
    const deleteModal = document.getElementById('deleteModal');
    
    if (addForm) addForm.style.display = 'none';
    if (deleteModal) deleteModal.style.display = 'none';
}


//delete class slot
function deleteSlot(slotID, studentID) {
    hideAllForms();
    
    const deleteModal = document.getElementById('deleteModal');
    const deleteSlotIDField = document.getElementById('deleteSlotID');
    const deleteStudentIDField = document.getElementById('deleteStudentID');
    
    if (deleteModal && deleteSlotIDField && deleteStudentIDField) {
       
        deleteSlotIDField.value = slotID;
        deleteStudentIDField.value = studentID;
        
        
        deleteModal.style.display = 'flex';
    }
}




function closeDeleteModal() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.style.display = 'none';
    }
}


const gradeTimeMap = {
    1: "Saturday 8-10 AM",
    2: "Saturday 10-12 AM", 
    3: "Saturday 12-2 PM",
    4: "Thursday 3-5 PM",
    5: "Friday 3-5 PM"
};


document.addEventListener('DOMContentLoaded', function() {
    // Add form functionality
    const addStudentSelect = document.getElementById('addStudentSelect');
    const addSlotID = document.getElementById('addSlotID');
    const addTime = document.getElementById('addTime');
    
    if (addStudentSelect && addSlotID && addTime) {
        addStudentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const gradeID = selectedOption.getAttribute('data-grade');
            
            if (gradeID) {
               
                const formattedGrade = gradeID.toString().padStart(2, '0');
                addSlotID.value = formattedGrade;
                
                
                addTime.value = gradeTimeMap[gradeID] || '';
            } else {
                addSlotID.value = '';
                addTime.value = '';
            }
        });
    }
    
});
