import React, { useEffect, useState } from 'react';
import { View, Text, Button, StyleSheet, ActivityIndicator } from 'react-native';
import Swiper from 'react-native-deck-swiper';
import { getPotentialMatches, swipeMatch } from '../services/api';

export default function SwipeScreen({ navigation }) {
    const [matches, setMatches] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    useEffect(() => { fetchMatches(); }, []);

    const fetchMatches = async () => {
    try {
        const response = await getPotentialMatches();
        setMatches(response.data.matches);
        } catch (err) {
            console.error('Failed to fetch matches:', err);
        } finally {
        setIsLoading(false);
        }
    };

    const handleSwipe = async (index, direction) => {
        if (index >= matches.length) return;
        const opponent = matches[index];
        const swipeResult = direction === 'right' ? 'accepted' : 'rejected';

    try {
        await swipeMatch({ opponent_username: opponent.username, swipe_result: swipeResult });
            console.log(`${swipeResult} swipe recorded for`, opponent.username);
        } catch (err) {
            console.error('Swipe error:', err);
        }
    };

    if (isLoading) {
        return <View style={styles.loading}><ActivityIndicator size="large" color="#ffcc00" /></View>;
    }

    if (matches.length === 0) {
        return <View style={styles.loading}><Text style={styles.text}>No matches found!</Text><Button title="Go Back" onPress={() => navigation.navigate('Dashboard')} /></View>;
    }

    return (
        <View style={styles.container}>
    <Swiper
      cards={matches}
      renderCard={(match) => (
        <View style={styles.card}>
        <Text style={styles.name}>{match.full_name}</Text>
        <Text style={styles.stats}>Weight: {match.weight} lbs</Text>
        <Text style={styles.stats}>Height: {match.height} in</Text>
        <Text style={styles.stats}>Bench: {match.bench_press} lbs</Text>
        <Text style={styles.stats}>Experience: {match.experience}</Text>
        <Text style={styles.stats}>Win Probability: {match.win_probability}%</Text>
        </View>
      )}
      onSwipedRight={(index) => handleSwipe(index, 'right')}
      onSwipedLeft={(index) => handleSwipe(index, 'left')}
      backgroundColor="#121212"
      cardVerticalMargin={50}
      stackSize={3}
    />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#121212' },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#121212' },
  card: { backgroundColor: '#2d3748', borderRadius: 10, padding: 20, justifyContent: 'center', alignItems: 'center', shadowColor: '#fff', shadowOffset: { width: 0, height: 5 }, shadowOpacity: 0.2, shadowRadius: 5 },
  name: { fontSize: 24, color: '#ffcc00', marginBottom: 10 },
  stats: { fontSize: 16, color: '#ffffff', marginVertical: 2 },
  text: { fontSize: 18, color: '#ffffff' },
});
