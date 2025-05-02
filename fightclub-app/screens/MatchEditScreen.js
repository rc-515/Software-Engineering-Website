import React, { useState } from 'react';
import {
  View,
  TextInput,
  Button,
  Alert,
  Text,
  StyleSheet,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { updateMatch } from '../services/api';

export default function MatchEditScreen({ route, navigation }) {
  const matchId = route.params?.match?.match_id;
  const match = route.params?.match;
  const user = route.params?.user;
  const [newDate, setNewDate] = useState('');

  const update = async () => {
    if (!newDate.trim()) {
      Alert.alert("Missing Date", "Please enter a date.");
      return;
    }

    const isValidDate =
      /^\d{4}-\d{2}-\d{2}$/.test(newDate) &&
      !isNaN(new Date(newDate).getTime());

    if (!isValidDate) {
      Alert.alert("Invalid Date", "Please enter a valid date in YYYY-MM-DD format.");
      return;
    }

    try {
      console.log("Attempting to update match:", { matchId, fight_date: newDate });

      const res = await updateMatch(matchId, { fight_date: newDate });

      console.log("✅ Update response:", res.data);
      Alert.alert("Updated!");
      navigation.navigate("Dashboard", { user });
    } catch (err) {
      console.log("❌ Update error:", err.response?.data || err.message || err);
      Alert.alert("Error", err.response?.data?.error || "Update failed");
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <Text style={styles.title}>Edit Match</Text>
      <Text style={styles.subtitle}>Original Date: {match?.fight_date}</Text>

      <TextInput
        placeholder="New Date (YYYY-MM-DD)"
        placeholderTextColor="#94a3b8"
        value={newDate}
        onChangeText={setNewDate}
        style={styles.input}
      />

      <View style={styles.buttonWrapper}>
        <Button title="Update Match" color="#ef4444" onPress={update} />
      </View>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    backgroundColor: '#0f172a',
    flex: 1,
    padding: 30,
    justifyContent: 'center',
  },
  title: {
    color: '#ffffff',
    fontSize: 26,
    fontWeight: 'bold',
    marginBottom: 10,
    textAlign: 'center',
  },
  subtitle: {
    color: '#cbd5e1',
    fontSize: 16,
    marginBottom: 25,
    textAlign: 'center',
  },
  input: {
    backgroundColor: '#334155',
    color: '#ffffff',
    padding: 12,
    marginBottom: 20,
    borderRadius: 6,
  },
  buttonWrapper: {
    marginTop: 10,
  },
});
