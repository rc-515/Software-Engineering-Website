import React, { useEffect, useState } from "react";
import {
  SafeAreaView,
  View,
  Text,
  Button,
  FlatList,
  StyleSheet,
  TouchableOpacity,
} from "react-native";
import { getMatches, deleteMatch } from "../services/api";

export default function DashboardScreen({ route, navigation }) {
  const user = route.params?.user;
  const email = user?.email;
  const [matches, setMatches] = useState([]);

  const load = async () => {
    const res = await getMatches();
    setMatches(res.data);
  };

  useEffect(() => {
    load();
  }, []);

  const renderMatch = ({ item }) => (
    <View style={styles.matchItem}>
      <Text style={styles.matchText}>
        <Text style={styles.nameHighlight}>{item.challenger_name}</Text> vs{" "}
        <Text style={styles.nameHighlight}>{item.opponent_name}</Text>
      </Text>
      <Text style={styles.dateText}>Date: {item.fight_date}</Text>
      {(item.challenger_name === email || item.opponent_name === email) && (
        <View style={styles.buttonRow}>
          <TouchableOpacity
            style={styles.editButton}
            onPress={() =>
              navigation.navigate("EditMatch", { match: item, user })
            }
          >
            <Text style={styles.buttonText}>Edit</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.deleteButton}
            onPress={async () => {
              await deleteMatch(item.match_id);
              load();
            }}
          >
            <Text style={styles.buttonText}>Delete</Text>
          </TouchableOpacity>
        </View>
      )}
    </View>
  );

  return (
    <SafeAreaView style={styles.container}>
      <Text style={styles.welcome}>Welcome, {user.full_name}</Text>

      <View style={styles.navButtonRow}>
        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate("CreateMatch", { user })}
        >
          <Text style={styles.actionButtonText}>Create Match</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.actionButton}
          onPress={() => navigation.navigate("Swipe", { user })}
        >
          <Text style={styles.actionButtonText}>Find Opponents</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.logoutButton}
          onPress={() =>
            navigation.reset({ index: 0, routes: [{ name: "Login" }] })
          }
        >
          <Text style={styles.logoutButtonText}>Log Out</Text>
        </TouchableOpacity>
      </View>

      <FlatList
        data={matches}
        keyExtractor={(item) => item.match_id.toString()}
        renderItem={renderMatch}
        contentContainerStyle={{ paddingBottom: 40 }}
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#0f172a",
    padding: 20,
  },
  welcome: {
    fontSize: 24,
    fontWeight: "bold",
    color: "#facc15", // yellow
    marginBottom: 20,
    textAlign: "center",
  },
  navButtonRow: {
    marginBottom: 25,
    alignItems: "center",
  },
  actionButton: {
    backgroundColor: "#facc15",
    padding: 12,
    borderRadius: 8,
    marginBottom: 10,
    width: "100%",
  },
  actionButtonText: {
    color: "#0f172a",
    fontWeight: "bold",
    textAlign: "center",
    fontSize: 16,
  },
  logoutButton: {
    backgroundColor: "#ef4444",
    padding: 12,
    borderRadius: 8,
    marginTop: 5,
    width: "100%",
  },
  logoutButtonText: {
    color: "#fff",
    fontWeight: "bold",
    textAlign: "center",
    fontSize: 16,
  },
  matchItem: {
    backgroundColor: "#1e293b",
    borderRadius: 8,
    padding: 15,
    marginBottom: 15,
  },
  matchText: {
    color: "#fff",
    fontSize: 16,
    marginBottom: 4,
  },
  nameHighlight: {
    color: "#facc15",
    fontWeight: "bold",
  },
  dateText: {
    color: "#cbd5e1",
    marginBottom: 10,
  },
  buttonRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    gap: 12,
  },
  editButton: {
    backgroundColor: "#0ea5e9",
    padding: 10,
    borderRadius: 6,
    flex: 1,
    marginRight: 6,
  },
  deleteButton: {
    backgroundColor: "#ef4444",
    padding: 10,
    borderRadius: 6,
    flex: 1,
    marginLeft: 6,
  },
  buttonText: {
    color: "#fff",
    fontWeight: "bold",
    textAlign: "center",
  },
});
