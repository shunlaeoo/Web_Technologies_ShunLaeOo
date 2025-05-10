const achievements = [
    {
      title: "First Step",
      description: "Complete your first workout",
      icon: "🏆",
      unlockedKey: "first_workout",
    },
    {
      title: "Workout Master",
      description: "Complete 5 workouts",
      icon: "🎖️",
      unlockedKey: "workout_5",
    },
    {
      title: "Streak Warrior",
      description: "Maintain a 3-day workout streak",
      icon: "⭐",
      unlockedKey: "streak_3",
    },
    {
      title: "Variety Champion",
      description: "Try all workout types",
      icon: "🏅",
      unlockedKey: "variety",
    },
    {
      title: "Fitness Elite",
      description: "Complete 10 workouts",
      icon: "🚀",
      unlockedKey: "workout_10",
    },
  ];
  
  export default function Achievements({ unlockedAchievements }) {
    const unlockedSet = new Set(unlockedAchievements || []);
    const unlockedCount = achievements.filter((a) => unlockedSet.has(a.unlockedKey)).length;
  
    return (
      <div className="bg-white rounded-xl shadow-sm mb-10">
        <div className="section-header bg-gradient-to-br from-pink-100 to-purple-50 py-4 px-6 rounded-t-lg flex justify-between items-center">
            <h2 className="text-2xl">
                <span className="heart-icon text-2xl animate-bounce mt-2">🏆</span> Achievements
            </h2>
            <span className="time-period">{unlockedCount} of 5 unlocked</span>
        </div>
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 p-6">
          {achievements.map((a, i) => {
            const isUnlocked = unlockedSet.has(a.unlockedKey);
            return (
              <div
                key={i}
                className={`rounded-xl p-5 text-center border ${
                  isUnlocked
                    ? "bg-pink-50 border-pink-200 text-pink-600"
                    : "bg-gray-50 border-gray-200 text-gray-400"
                }`}
              >
                <div className="mb-2 text-3xl">{a.icon}</div>
                <h3 className="font-semibold">{a.title}</h3>
                <p className="text-sm">{a.description}</p>
                <div className="mt-2">
                  <span
                    className={`text-xs px-2 py-1 rounded-full font-medium ${
                      isUnlocked
                        ? "bg-gradient-to-r from-pink-500 to-purple-500 text-white"
                        : "bg-gray-200 text-gray-500"
                    }`}
                  >
                    {isUnlocked ? "Unlocked" : "Locked"}
                  </span>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    );
}  