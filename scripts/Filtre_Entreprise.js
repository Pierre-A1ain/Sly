// création ticket > Filtre ENTREPRISE 
const searchInput = document.getElementById('searchInput');
const selectMenu = document.getElementById('entreprise');
        
searchInput.addEventListener('input', function() {
    const searchText = this.value.toLowerCase();
    for (let option of selectMenu.options) {
        const optionText = option.textContent.toLowerCase();
        option.style.display = optionText.includes(searchText) ? '' : 'none';
    }
});