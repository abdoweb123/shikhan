
@php
  $testResults = resultsService()->getStudentTestResults($student, $currentTest);
@endphp

@if ($testResults->isNotEmpty())
  <div class="container-fluid" style="padding-top: 5px;">
    <div class="row">
      <div class="text-left col-md-12">
          <div class="row">
              @foreach ($testResults as $testResult)

                  <div class="col-md-12">
                    @if ($testResult->isSuccessed())
                      <div class="alert alert-success" style="text-align: center;" role="alert">
                        {{-- $testResult->getRate() --}}, ( {{ $testResult->degree }} % ), {{ $testResult->created_at }}
                      </div>
                    @else
                      <div class="alert alert-danger" style="text-align: center;" role="alert">
                        {{-- $testResult->getRate() --}}, ( {{ $testResult->degree }} % ), {{ $testResult->created_at }}
                      </div>
                    @endif
                  </div>

              @endforeach
          </div>
      </div>
    </div>
  </div>
@endif
