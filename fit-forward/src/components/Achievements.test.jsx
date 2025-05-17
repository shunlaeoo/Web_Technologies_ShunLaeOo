import { render, screen } from '@testing-library/react';
import Achievements from './Achievements';

describe('Achievements Component', () => {
  test('renders achievements correctly', () => {
    render(<Achievements unlockedAchievements={[]} />);
    
    // Check if title is rendered
    expect(screen.getByText('Achievements')).toBeInTheDocument();
    
    // Check if all 5 achievements are rendered
    expect(screen.getByText('First Step')).toBeInTheDocument();
    expect(screen.getByText('Workout Master')).toBeInTheDocument();
    expect(screen.getByText('Streak Warrior')).toBeInTheDocument();
    expect(screen.getByText('Variety Champion')).toBeInTheDocument();
    expect(screen.getByText('Fitness Elite')).toBeInTheDocument();
  });

  test('shows correct unlock count', () => {
    render(<Achievements unlockedAchievements={['first_workout', 'workout_5']} />);
    expect(screen.getByText('2 of 5 unlocked')).toBeInTheDocument();
  });

  test('displays correct status for unlocked achievements', () => {
    render(<Achievements unlockedAchievements={['first_workout']} />);
    
    // First achievement should be unlocked
    const unlocked = screen.getAllByText('Unlocked');
    expect(unlocked).toHaveLength(1);
    
    // Other achievements should be locked
    const locked = screen.getAllByText('Locked');
    expect(locked).toHaveLength(4);
  });
  
  // Additional tests you could add:
  test('displays achievement descriptions correctly', () => {
    render(<Achievements unlockedAchievements={[]} />);
    
    expect(screen.getByText('Complete your first workout')).toBeInTheDocument();
    expect(screen.getByText('Complete 5 workouts')).toBeInTheDocument();
    expect(screen.getByText('Maintain a 3-day workout streak')).toBeInTheDocument();
  });
  
  test('displays achievement icons', () => {
    render(<Achievements unlockedAchievements={[]} />);
    
    // Check for icons (this is a simple approach - you might need to adjust based on how icons are rendered)
    expect(screen.getAllByText('🏆')).toHaveLength(2); // One in header, one in first achievement
    expect(screen.getByText('🎖️')).toBeInTheDocument();
    expect(screen.getByText('⭐')).toBeInTheDocument();
  });
});
