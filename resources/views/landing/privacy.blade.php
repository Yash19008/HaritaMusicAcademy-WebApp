@extends('layouts.landing')
@section('content')


        <section class="policy-section">
            <div class="container policy-container">
                <div class="policy-card">

                    <!-- Your Privacy Policy Content -->

                    <div class="policy-highlight">
                        <strong>Important Notice</strong>
                        <p>
                            The Academy reserves the right to amend these policies at any time.
                            Any updates will be published on this page and become effective immediately.
                        </p>
                    </div>

                    <div class="container">


                        <h1 class="section-title">
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    Non Teaching Staff
                                @elseif(auth()->user()->hasRole('teacher'))
                                    Teacher Policy
                                @elseif(auth()->user()->hasRole('student'))
                                    Student Policy
                                @else
                                    Privacy Policy & Academy Policies
                                @endif
                            @else
                                Privacy Policy & Academy Policies
                            @endauth
                        </h1>

                        <p class="section-description">
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    This privacy policy section applies exclusively to non-teaching staff and system administrators of Harita Music Academy.
                                @elseif(auth()->user()->hasRole('teacher'))
                                    This privacy policy section applies exclusively to teachers and mentors of Harita Music Academy.
                                @elseif(auth()->user()->hasRole('student'))
                                    This privacy policy section applies exclusively to enrolled students of Harita Music Academy.
                                @else
                                    Harita Music Academy is committed to protecting your privacy and maintaining a transparent learning environment for all students, parents, and teachers. By using our website or enrolling in our classes, you agree to the following policies.
                                @endif
                            @else
                                Harita Music Academy is committed to protecting your privacy and maintaining a transparent learning environment for all students, parents, and teachers. By using our website or enrolling in our classes, you agree to the following policies.
                            @endauth
                        </p>

                        <hr style="margin:40px 0;">

                        <h2>Class Rescheduling Policy</h2>

                        <h3>National Students (India)</h3>

                        <ul>
                            <li>Students can reschedule a class only if the request is submitted at least <strong>10
                                    hours</strong> before the scheduled class.</li>
                            <li>Teachers must reschedule or cancel a class at least <strong>8 hours</strong> before the
                                scheduled class.</li>
                            <li>Each student may reschedule a maximum of <strong>2 classes per calendar month</strong>.
                            </li>
                            <li>Requests made after the permitted time will automatically be rejected.</li>
                        </ul>

                        <h3>International Students</h3>

                        <ul>
                            <li>Students can reschedule a class only if the request is submitted at least <strong>12
                                    hours</strong> before the scheduled class.</li>
                            <li>Teachers must reschedule or cancel a class at least <strong>10 hours</strong> before the
                                scheduled class.</li>
                            <li>Each student may reschedule a maximum of <strong>2 classes per calendar month</strong>.
                            </li>
                            <li>Late requests will automatically be rejected.</li>
                        </ul>

                        <hr>

                        <h2>No-Show & Late Cancellation Policy</h2>

                        <ul>
                            <li>If a student does not attend without prior approval, the class will be marked as a
                                <strong>Student No-Show</strong>.
                            </li>
                            <li>If a teacher does not attend without prior approval, the class will be marked as a
                                <strong>Teacher No-Show</strong>.
                            </li>
                            <li>Late cancellations or reschedule requests after the permitted time will be treated as
                                No-Shows unless approved by the Academy in genuine emergency situations.</li>
                            <li>No-Show classes are not eligible for refunds, compensation, or rescheduling.</li>
                            <li>Repeated No-Show behaviour may result in account review or further action by the
                                Academy.</li>
                        </ul>

                        <hr>

                        <h2>General Academy Policy</h2>

                        <ul>
                            <li>Teachers must submit leave requests with a valid reason and receive Admin approval
                                before leave becomes effective.</li>
                            <li>Where possible, another qualified teacher may be assigned if a class is cancelled within
                                the permitted time.</li>
                            <li>Attendance records include Present, Absent, and No-Show status for both students and
                                teachers.</li>
                            <li>Approved rescheduled classes will automatically update the class calendar and notify
                                both the student and teacher.</li>
                            <li>The Academy reserves the final authority to approve exceptions only in genuine emergency
                                situations.</li>
                        </ul>

                        <hr>

                        <h2>Privacy Policy</h2>

                        <p>
                            Harita Music Academy respects your privacy and protects your personal information.
                            We collect only the information necessary to provide our educational services,
                            process payments, schedule classes, communicate important updates, and improve
                            your learning experience.
                        </p>

                        <p>
                            Your information will never be sold to third parties. We may share limited
                            information only with trusted service providers involved in payment processing,
                            communication, or technical support where required to deliver our services.
                        </p>

                        <p>
                            By enrolling in our courses or using our website, you consent to the collection,
                            storage, and use of your information in accordance with this Privacy Policy.
                        </p>

                        <hr>

                        <p style="margin-top:40px;">
                            <strong>Last Updated:</strong> July 2026
                        </p>

                    </div>
                </div>
            </div>
        </section>

    
@endsection