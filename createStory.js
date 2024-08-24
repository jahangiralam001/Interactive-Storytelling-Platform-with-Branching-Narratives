document.addEventListener('DOMContentLoaded', () => {
    const sectionsContainer = document.getElementById('sections-container');
    let sectionCount = 1;

    document.getElementById('add-section').addEventListener('click', () => {
        sectionCount++;
        const sectionDiv = document.createElement('div');
        sectionDiv.className = 'section';
        sectionDiv.id = `section-${sectionCount}`;
        sectionDiv.innerHTML = `
            <div class="form-group">
                <label for="section-title-${sectionCount}">Section Title:</label>
                <input type="text" id="section-title-${sectionCount}" name="section-title-${sectionCount}" required>
            </div>
            <div class="form-group">
                <label for="section-body-${sectionCount}">Section Body:</label>
                <textarea id="section-body-${sectionCount}" name="section-body-${sectionCount}" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label>Branches:</label>
                <div class="branch">
                    <input type="text" name="branch-${sectionCount}-1" placeholder="Option A">
                </div>
                <div class="branch">
                    <input type="text" name="branch-${sectionCount}-2" placeholder="Option B">
                </div>
            </div>
            <button type="button" class="remove-section">Remove Section</button>
        `;
        sectionsContainer.appendChild(sectionDiv);

        // Add event listener to new remove section button
        sectionDiv.querySelector('.remove-section').addEventListener('click', () => {
            sectionDiv.remove();
        });
    });

    // Delegate event for removing sections
    sectionsContainer.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-section')) {
            event.target.closest('.section').remove();
        }
    });
});
