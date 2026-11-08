const subjectSelect = document.querySelector('#subject');

async function loadSubjects() {
  try {
    const response = await fetch('subjects.php');
    if (!response.ok) {
      throw new Error(`subjects.php failed: ${response.status}`);
    }
    const subjects = await response.json();

    for (const subject of subjects) {
      const option = document.createElement('option');
      option.value = subject.id;
      option.textContent = subject.name;
      subjectSelect.append(option);
    }
  } catch (error) {
    console.error(error);
  }
}

loadSubjects();