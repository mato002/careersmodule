<div class="aptitude-test-content">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Aptitude Test</h1>
        <p class="text-gray-600">Position: <span class="font-semibold">{{ $application->jobPost->title ?? 'N/A' }}</span></p>
        <p class="text-sm text-gray-500 mt-1">Time Limit: 30 minutes | Total Questions: {{ $questions->count() }}</p>
    </div>

    <!-- Timer -->
    <div class="mb-6 bg-teal-50 border border-teal-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-teal-900">Time Remaining:</span>
            <span id="timer" class="text-2xl font-bold text-teal-700">30:00</span>
        </div>
    </div>

    <!-- Test Form -->
    <form id="testForm" method="POST" action="{{ route('aptitude-test.submit', $application) }}">
        @csrf
        
        @php
            $sectionOrder = ['numerical', 'logical', 'verbal', 'scenario'];
            $currentSection = null;
        @endphp

        @foreach($questions as $index => $question)
            @if($currentSection !== $question->section)
                @if($currentSection !== null)
                    </div>
                @endif
                @php
                    $currentSection = $question->section;
                    $sectionTitles = [
                        'numerical' => 'Section A: Numerical & Analytical',
                        'logical' => 'Section B: Logical Reasoning',
                        'verbal' => 'Section C: Verbal & Comprehension',
                        'scenario' => 'Section D: Job-Fit Scenarios'
                    ];
                @endphp
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-teal-500">
                        {{ $sectionTitles[$question->section] ?? ucfirst($question->section) }}
                    </h2>
            @endif

            <div class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Question {{ $index + 1 }}</span>
                    <span class="text-xs text-gray-400">{{ $question->points }} points</span>
                </div>
                
                <p class="text-gray-900 font-medium mb-4 leading-relaxed">{{ $question->question }}</p>
                
                <div class="space-y-3">
                    @foreach($question->options as $key => $option)
                        <label class="flex items-start p-3 rounded-lg border-2 border-gray-200 hover:border-teal-400 cursor-pointer transition">
                            <input type="radio" 
                                   name="answers[{{ $question->id }}]" 
                                   value="{{ $key }}"
                                   class="mt-1 mr-3 w-4 h-4 text-teal-600 focus:ring-teal-500"
                                   @if(old("answers.{$question->id}") === $key) checked @endif>
                            <span class="flex-1 text-gray-700">
                                <span class="font-semibold mr-2">{{ strtoupper($key) }}.</span>
                                {{ $option }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
        </div>

        <!-- Submit Button -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <button type="submit" 
                    id="submitBtn"
                    class="w-full sm:w-auto px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl">
                Submit Test
            </button>
            <p class="text-sm text-gray-500 mt-3">Make sure you've answered all questions before submitting.</p>
        </div>
    </form>
</div>

<script>
let timeLeft = 30 * 60; // 30 minutes in seconds
const timerElement = document.getElementById('timer');
const form = document.getElementById('testForm');
let timerInterval;

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        alert('Time is up! Your test will be submitted automatically.');
        form.submit();
    }
    
    // Warning at 5 minutes
    if (timeLeft === 5 * 60) {
        timerElement.classList.add('text-red-600');
        alert('5 minutes remaining!');
    }
    
    timeLeft--;
}

// Start timer
timerInterval = setInterval(updateTimer, 1000);
updateTimer();

// Prevent accidental navigation
window.addEventListener('beforeunload', function(e) {
    if (!form.querySelector('[type="submit"]').disabled) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Confirm submission
form.addEventListener('submit', function(e) {
    const unanswered = form.querySelectorAll('input[type="radio"]:checked').length;
    const totalQuestions = {{ $questions->count() }};
    
    if (unanswered < totalQuestions) {
        if (!confirm(`You have answered ${unanswered} out of ${totalQuestions} questions. Are you sure you want to submit?`)) {
            e.preventDefault();
            return false;
        }
    }
    
    clearInterval(timerInterval);
    form.querySelector('[type="submit"]').disabled = true;
});
</script>

