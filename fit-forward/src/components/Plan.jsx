import { toast } from 'react-toastify';
import axios from 'axios';
import { useEffect, useState } from 'react';

function Plan({ recommendationsRef }) {
    const [workoutPlan, setWorkoutPlan] = useState([]);
    const [mealPlan, setMealPlan] = useState([]);
    const [dailyCalories, setDailyCalories] = useState(null);

    useEffect(() => {
        const fetchPlans = async () => {
            const loadingToast = toast.loading('Fetching your plan...');
            try {
                const res = await axios.get('http://127.0.0.1:8000/api/plans', {
                withCredentials: true,
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            });
    
            const { exercises, meal_plan, daily_calories } = res.data;
    
            setWorkoutPlan(exercises);
            setMealPlan(meal_plan);
            setDailyCalories(daily_calories);

            toast.update(loadingToast, {
                render: 'Workout plan loaded!',
                type: 'success',
                isLoading: false,
                autoClose: 2000
            });
          } catch (error) {
            console.error('Failed to fetch plans:', error);
            toast.update(loadingToast, {
                render: 'Failed to load plan.',
                type: 'error',
                isLoading: false,
                autoClose: 2000
            });
          }
        };
        setTimeout(fetchPlans, 100);
    }, []);

    const [selectedExercise, setSelectedExercise] = useState(null);
    const [selectedVideo, setSelectedVideo] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);

    const handleViewClick = (name, videoUrl) => {
        setSelectedExercise(name);
        setSelectedVideo(videoUrl);
        setIsModalOpen(true);
    };

    const closeModal = () => {
        setIsModalOpen(false);
        setSelectedExercise(null);
        setSelectedVideo(null);
    };

    const extractYouTubeID = (url) => {
        const regex = /(?:\?v=|\/embed\/|\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = url.match(regex);
        return match ? match[1] : '';
    };

    const [user, setUser] = useState(null);
    const [completedWorkouts, setcompletedWorkouts] = useState([]);
    useEffect(() => {
        const token = localStorage.getItem('token');
      
        const fetchUser = async () => {
          try {
            const res = await axios.get('http://127.0.0.1:8000/api/user', {
              headers: {
                Authorization: `Bearer ${token}`,
              },
              withCredentials: true,
            });

            const { user, completedWorkouts } = res.data;
            setUser(user);
            setcompletedWorkouts(completedWorkouts);
            
          } catch (error) {
            console.error('Error fetching user:', error);
            toast.error('Unauthorized: please login again');
            // console.log(res.data); // Removed as 'res' is not defined in this scope
          }
        };
      
        fetchUser();
    }, []);      
    
    const handleComplete = async (exerciseId, workoutPlanId) => {
        setcompletedWorkouts(prev => [...prev, exerciseId]);

        const token = localStorage.getItem('token');
        const userId = user?.id;
      
        if (!userId || !token) {
          toast.error('User not authenticated');
          return;
        }
      
        try {
          await axios.post(
            'http://127.0.0.1:8000/api/workout_complete',
            {
              user_id: userId,
              exercise_id: exerciseId,
              workout_plan_id: workoutPlanId,
              completed: true,
            },
            {
              headers: {
                Authorization: `Bearer ${token}`,
              },
              withCredentials: true,
            }
          );
          toast.success('Workout marked as complete!');
        } catch (error) {
            console.error(error);
            toast.error('Failed to complete workout.');
            setcompletedWorkouts(prev => prev.filter(id => id !== exerciseId));
        }
    };      

    return (
        <div 
            ref={recommendationsRef}
            id="recommendation"
            className="bg-gradient-to-br from-pink-50 to-purple-50 p-6 md:pb-10 md:pt-8 md:px-10">
            <h2 className="text-3xl text-pink-600 font-bold mb-6">Recommendations</h2>
            <div className="flex flex-col lg:flex-row">        
                {/* Workout Plan */}
                <div className="w-full lg:w-1/2 bg-white rounded-xl shadow-md mr-0 md:mr-6 mb-6 lg:mb-0">
                    <div className="bg-gradient-to-br from-pink-100 to-purple-50 rounded-t-lg p-6">
                        <h2 className="text-xl font-semibold flex items-center mb-1">
                            <span className="mr-1 animate-bounce">
                                <svg className="text-pink-600" 
                                    aria-hidden="true" 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    width="25" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" strokeLinecap="round" 
                                        strokeLinejoin="round" strokeWidth="2" d="M8 18V6l8 6-8 6Z">
                                    </path>
                                </svg>
                            </span> 
                            Workout Plan Recommendations
                        </h2>
                        <p className="text-sm text-gray-500">Personalized for your profile</p>
                    </div>
                    <div className="p-6">
                        {workoutPlan ? (
                            workoutPlan.map((item, idx) => (
                                <div
                                    key={idx}
                                    className="bg-white rounded-lg p-4 mb-4 shadow-sm"
                                >
                                    <div className='flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3'>
                                        <div className='flex items-center gap-5'>
                                            <div className="text-pink-600 font-semibold">
                                                {item.exercise?.name}
                                            </div>
                                            <div className="inline-block bg-pink-100 text-pink-500 text-sm font-bold rounded-full px-3 py-1 mt-2">
                                                {item.sets} × {item.reps ? `${item.reps}` : `${item.duration} s`}
                                            </div>
                                        </div>
                                        <div className="flex space-x-2 mt-4 sm:mt-0">
                                            <button onClick={() => handleViewClick(item.exercise?.name, item.exercise?.video_url)}
                                                className="view flex rounded px-3 py-0 text-sm transition">
                                                <svg aria-hidden="true" 
                                                    xmlns="http://www.w3.org/2000/svg" 
                                                    width="20" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" strokeLinecap="round" 
                                                        strokeLinejoin="round" strokeWidth="2" d="M8 18V6l8 6-8 6Z">
                                                    </path>
                                                </svg>
                                                <span className="pt-0.5">View</span>
                                            </button>
                                            <button
                                                onClick={() => handleComplete(item.exercise.id, item.workout_plan_id)}
                                                disabled={completedWorkouts.includes(item.exercise.id)}
                                                className={`rounded px-3 py-0 text-sm transition flex items-center space-x-1
                                                    ${completedWorkouts.includes(item.exercise.id) ? 'completed' : 'complete'}`}
                                                >
                                                {completedWorkouts.includes(item.exercise.id) ? (
                                                    <>
                                                    <svg
                                                        className="w-5 h-5"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        strokeWidth={2}
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Completed</span>
                                                    </>
                                                ) : (
                                                    <span>Complete</span>
                                                )}
                                                </button>
                                        </div>
                                    </div>
                                    <div className='flex flex-col sm:flex-row items-start sm:items-center justify-between'>
                                        <div>
                                            <p className='mt-3 mb-1 font-semibold'>Instructions</p>
                                            <p className='px-6 pt-1 pb-3' dangerouslySetInnerHTML={{ __html: item.exercise?.instructions }} />
                                        </div>
                                        {item.exercise?.image && (
                                            <img
                                                src={`http://localhost:8000/storage/${item.exercise?.image}`}
                                                alt={item.exercise?.name}
                                                className="w-50"
                                            />
                                        )}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <p>Loading...</p>
                        )}
                    </div>
                </div>

                {/* Nutrition Recommendations */}
                <div className="w-full lg:w-1/2 bg-white rounded-xl shadow-md mr-0 md:mr-6 mb-6 md:mb-0">
                    <div className="bg-gradient-to-br from-pink-100 to-purple-50 rounded-t-lg p-6">
                        <h2 className="text-xl font-semibold flex items-center mb-1">
                            <span className="mr-1">
                                <svg className="text-pink-600 animate-bounce me-2" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" data-lov-id="src/components/Profile.tsx:461:16" data-lov-name="Utensils" data-component-path="src/components/Profile.tsx" data-component-line="461" data-component-file="Profile.tsx" data-component-name="Utensils" data-component-content="%7B%22className%22%3A%22text-primary%20h-5%20w-5%22%7D">
                                    <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
                                </svg>
                            </span> 
                            Nutrition Recommendations
                        </h2>
                        <p className="text-sm text-gray-500">Personalized plan based on your profile</p>
                    </div>
                    <div className="flex justify-between gap-3 m-6">
                        <div className="flex-1 text-center rounded-lg shadow-sm p-4">
                            <div className="text-gray-500 font-semibold">Protein</div>
                            <div className="text-pink-600 font-bold text-2xl">{mealPlan.protein}%</div>
                        </div>
                        <div className="flex-1 text-center rounded-lg shadow-sm p-4">
                            <div className="text-gray-500 font-semibold">Carbs</div>
                            <div className="text-pink-600 font-bold text-2xl">{mealPlan.carbs}%</div>
                        </div>
                        <div className="flex-1 text-center rounded-lg shadow-sm p-4">
                            <div className="text-gray-500 font-semibold">Fats</div>
                            <div className="text-pink-600 font-bold text-2xl">{mealPlan.fats}%</div>
                        </div>
                    </div>
                    <div className="bg-pink-50 rounded-lg shadow-sm p-6 m-6 text-center">
                        <div className="text-gray-500 font-semibold mb-3">Daily Calories Target</div>
                        <div className="text-pink-600 font-bold text-3xl">{dailyCalories} kcal</div>
                    </div>
                    <div className="bg-pink-50 rounded-lg shadow-sm p-6 m-6">
                        <div className="font-semibold mb-3">Sample Meal Plan</div>
                        <p className='nut px-6' dangerouslySetInnerHTML={{ __html: mealPlan.description }} />
                    </div>
                </div>
            </div>

            {isModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center">
                    <div className="bg-white rounded-xl shadow-lg w-[90%] md:w-[640px] max-w-[100vw] p-6 relative">
                        <h2 className="text-xl font-semibold flex items-center mb-3">
                            <span className="mr-1">
                                <svg className="text-pink-600" 
                                    aria-hidden="true" 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    width="25" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" strokeLinecap="round" 
                                        strokeLinejoin="round" strokeWidth="2" d="M8 18V6l8 6-8 6Z">
                                    </path>
                                </svg>
                            </span> 
                            {selectedExercise} Tutorial
                        </h2>
                        <button
                            onClick={closeModal}
                            className="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-xl font-bold"
                        >
                            &times;
                        </button>
                        {selectedVideo ? (
                            <div className="aspect-w-16 aspect-h-9">
                                <iframe
                                    className="w-full h-72 md:h-96"
                                    src={`https://www.youtube.com/embed/${extractYouTubeID(selectedVideo)}`}
                                    title="Exercise Video"
                                    allowFullScreen
                                />
                            </div>
                        ) : (
                        <p>No video available</p>
                    )}
                    </div>
                </div>
            )}

        </div>
    );
}

export default Plan;
