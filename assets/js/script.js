/**
 * Main JavaScript
 * Path: assets/js/script.js
 */

// Multi-select dropdown functionality
let picItOpen = false;

function togglePicIt() {
    const panel = document.getElementById("picItPanel");
    if (!panel) return;
    
    picItOpen = !picItOpen;
    panel.style.display = picItOpen ? "block" : "none";
}

function updateSelectedText() {
    const checked = document.querySelectorAll('#picItPanel input.cb:checked');
    const selectedText = document.getElementById("selectedText");
    
    if (!checked || !selectedText) return;
    
    const values = Array.from(checked).map(cb => cb.value);
    selectedText.innerText = values.length ? values.join(", ") : "Pilih PIC IT Support";
}

// Close dropdown when clicking outside
document.addEventListener("click", function(e) {
    const wrap = document.getElementById("picItDropdown");
    const panel = document.getElementById("picItPanel");
    
    if (wrap && panel && !wrap.contains(e.target)) {
        panel.style.display = "none";
        picItOpen = false;
    }
});

// Initialize multi-select
document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll('#picItPanel input.cb');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedText);
    });
    updateSelectedText();
});

// Modal functions for Admin Panel
function editPic(id, name) {
    const idInput = document.getElementById('editPicId');
    const nameInput = document.getElementById('editPicName');
    const modal = document.getElementById('modalEditPic');
    
    if (idInput && nameInput && modal) {
        idInput.value = id;
        nameInput.value = name;
        modal.classList.add('show');
    }
}

function editRoom(id, name) {
    const idInput = document.getElementById('editRoomId');
    const nameInput = document.getElementById('editRoomName');
    const modal = document.getElementById('modalEditRoom');
    
    if (idInput && nameInput && modal) {
        idInput.value = id;
        nameInput.value = name;
        modal.classList.add('show');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('show');
    }
}

// Confirmation for delete actions
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
});