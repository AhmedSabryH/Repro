import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fortawesome/fontawesome-free/js/all.min.js';


document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('accountType').addEventListener('change', function () {
        const container = document.getElementById('otherAccountContainer');
        if (this.value === 'Other') {
            container.classList.remove('hidden');
            container.classList.add('block');
        } else {
            container.classList.add('hidden');
            container.classList.remove('block');
        }
    });

// Add step functionality
    let stepCount = 0;
    document.getElementById('addStepBtn').addEventListener('click', function () {
        const list = document.getElementById('stepsList');
        const noMsg = document.getElementById('noStepsMessage');

        if (noMsg) noMsg.remove();

        stepCount++;
        const stepDiv = document.createElement('div');
        stepDiv.className = 'step-item flex items-start gap-4 p-4 bg-dark-800/50 border border-white/10 rounded-xl transition-all duration-300 hover:border-primary/30 hover:shadow-glow group';
        stepDiv.innerHTML = `
                <div class="step-number w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-glow">
                    ${stepCount}
                </div>
                <textarea
                    class="step-input flex-1 bg-transparent border-none text-gray-200 placeholder-gray-600 focus:outline-none resize-y min-h-[60px] font-mono text-sm"
                    placeholder="${window.translations.describe_step} ${stepCount}..."
                    rows="2"></textarea>
                <button type="button"
                    class="w-8 h-8 rounded-full bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 flex items-center justify-center transition-all duration-300 hover:scale-110 flex-shrink-0 group-hover:opacity-100 opacity-70">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;
        list.appendChild(stepDiv);
        stepDiv.scrollIntoView({behavior: 'smooth', block: 'nearest'});
    });

    document.getElementById('stepsList').addEventListener('click', function (e) {
        console.log(window.translations.describe_step)
        if (e.target.closest('button')) {
            const btn = e.target.closest('button');
            removeStep(btn);
        }
    });

    function removeStep(btn) {
        const item = btn.closest('.step-item');
        item.remove();
        renumberSteps();
        if (document.querySelectorAll('.step-item').length === 0) {
            document.getElementById('stepsList').innerHTML = `
                    <div class="no-steps-message text-center py-8 text-gray-500 border-2 border-dashed border-white/10 rounded-xl bg-dark-800/30" id="noStepsMessage">
                        <i class="fas fa-hand-pointer text-3xl mb-2 text-primary/50"></i>
                        <p>${window.translations.click_add_step} <span class="text-primary font-semibold">"${window.translations.add_step}"</span> ${window.translations.begin_adding_steps}</p>
                    </div>
                `;
            stepCount = 0;
        }
    }

    function renumberSteps() {
        const items = document.querySelectorAll('.step-item');
        items.forEach((item, index) => {
            item.querySelector('.step-number').textContent = index + 1;
        });
        stepCount = items.length;
    }

// Validation and form handling
    const form = document.getElementById('testCaseForm');
    const copyBtn = document.getElementById('copyBtn');
    const outputArea = document.getElementById('outputArea');

    function validateForm() {
        const required = form.querySelectorAll('[required]');
        let valid = true;
        required.forEach(field => {
            if (!field.value.trim()) valid = false;
        });

        const steps = document.querySelectorAll('.step-item textarea');
        if (steps.length === 0) valid = false;

        copyBtn.disabled = !valid;
        return valid;
    }

    form.addEventListener('input', function () {
        validateForm();
        updateOutput();
    });

    function updateOutput() {
        const data = {
            testType: document.getElementById('testType').value,
            errorUrl: document.getElementById('errorUrl').value,
            steps: Array.from(document.querySelectorAll('.step-item textarea')).map((t, i) => `${i + 1}. ${t.value}`).join('\n'),
            accountType: document.getElementById('accountType').value === 'Other'
                ? document.getElementById('otherAccount').value
                : document.getElementById('accountType').value,
            environment: document.getElementById('testEnvironment').value,
            date: document.getElementById('testDate').value,
            expected: document.getElementById('expectedResult').value,
            actual: document.getElementById('actualResult').value,
            cause: document.getElementById('suspectedCause').value,
            solution: document.getElementById('suggestedSolution').value
        };

        const output = `*Test Type:* ${data.testType}
*Error URL:* ${data.errorUrl}

*Steps to Reproduce:*
${data.steps || 'No steps added'}

*Account Type:* ${data.accountType || 'Not specified'}
*Environment:* ${data.environment || 'Not specified'}
*Date:* ${data.date || 'Not specified'}

*Expected Result:*
${data.expected || 'Not specified'}

*Actual Result:*
${data.actual || 'Not specified'}

*Suspected Cause:*
${data.cause || 'Not specified'}

${data.solution ? `*Suggested Solution:*\n${data.solution}` : ''}`;

        outputArea.textContent = output;
    }

    copyBtn.addEventListener('click', function () {
        const text = outputArea.textContent;
        navigator.clipboard.writeText(text).then(() => {
            const notif = document.getElementById('notification');
            notif.classList.add('show');
            setTimeout(() => notif.classList.remove('show'), 3000);
        });
    });

// Initialize
    validateForm();
})
