import React, { useEffect, useState } from 'react';
import { View, Text, Button, StyleSheet, ActivityIndicator, Alert } from 'react-native';
import Swiper from 'react-native-deck-swiper';
import { getPotentialMatches, swipeMatch, createMatch } from '../services/api';

export default function SwipeScreen({ navigation, route }) {
  const [matches, setMatches] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const user = route?.params?.user;

  useEffect(() => {
    fetchMatches();
  }, []);

  const fetchMatches = async () => {
    try {
      const response = await getPotentialMatches(user.email);
      setMatches(response.data?.matches || []);
    } catch (err) {
      console.error('Failed to fetch matches:', err);
      setMatches([]);
    } finally {
      setIsLoading(false);
    }
  };

  const generateRandomDate = () => {
    const today = new Date();
    const offsetDays = Math.floor(Math.random() * 60) + 30;
    const fightDate = new Date(today.setDate(today.getDate() + offsetDays));
    return fightDate.toISOString().split("T")[0];
  };

  const handleSwipe = async (index, direction) => {
    if (index >= matches.length) return;
    const opponent = matches[index];
    const swipeResult = direction === 'right' ? 'accepted' : 'rejected';

    try {
      const response = await swipeMatch({
        email: user.email,
        opponent_username: opponent.username,
        swipe_result: swipeResult,
      });
      console.log("Swipe result for", opponent.username, "→", response.data);

      if (swipeResult === 'accepted' && response.data.result === 'match') {
        const fight_date = generateRandomDate();
        await createMatch({
          challenger_name: user.email,
          opponent_name: opponent.username,
          fight_date,
        });
        Alert.alert("Match Found!", "A fight has been scheduled.");
      }
      
    } catch (err) {
      console.error('Swipe error:', err);
    }
  };

  if (isLoading) {
    return (
      <View style={styles.loading}>
        <ActivityIndicator size="large" color="#ffcc00" />
      </View>
    );
  }

  if (!matches || matches.length === 0) {
    return (
      <View style={styles.loading}>
        <Text style={styles.text}>No matches found!</Text>
        <Button
          title="Back to Dashboard"
          onPress={() => navigation.navigate('Dashboard', { user })}
          color="#ffcc00"
        />
      </View>
    );
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

      <View style={styles.backButtonContainer}>
        <Button
          title="Back to Dashboard"
          onPress={() => navigation.navigate('Dashboard', { user })}
          color="#ffcc00"
        />
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#121212' },
  loading: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#121212' },
  card: {
    backgroundColor: '#2d3748',
    borderRadius: 10,
    padding: 20,
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#fff',
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.2,
    shadowRadius: 5,
  },
  name: { fontSize: 24, color: '#ffcc00', marginBottom: 10 },
  stats: { fontSize: 16, color: '#ffffff', marginVertical: 2 },
  text: { fontSize: 18, color: '#ffffff', marginBottom: 20 },
  backButtonContainer: {
    padding: 20,
    alignItems: 'center',
    marginBottom: 30,
  },
});
